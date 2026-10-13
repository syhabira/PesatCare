<?php

namespace App\Services;

use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\MedicineStockMovement;
use App\Models\User;
use App\Models\Visit;
use App\Models\VisitMedicine;
use Illuminate\Validation\ValidationException;

/**
 * Semua method WAJIB dipanggil di dalam DB::transaction().
 * Pengurangan memakai FEFO (batch kedaluwarsa terdekat dulu) dengan lockForUpdate.
 */
class StockService
{
    /** @param array<int,array{medicine_id:int,quantity:int,notes?:?string}> $items */
    public function dispense(Visit $visit, array $items, User $by): void
    {
        // gabungkan duplikat & urutkan agar urutan lock konsisten (cegah deadlock)
        $need = [];
        foreach ($items as $i) {
            $id = (int) $i['medicine_id'];
            $need[$id] = [
                'qty' => ($need[$id]['qty'] ?? 0) + (int) $i['quantity'],
                'notes' => $i['notes'] ?? ($need[$id]['notes'] ?? null),
            ];
        }
        ksort($need);

        foreach ($need as $medicineId => $n) {
            $medicine = Medicine::findOrFail($medicineId);
            if (! $medicine->is_active) {
                $this->fail("Obat {$medicine->name} sudah nonaktif.");
            }

            $batches = MedicineBatch::where('medicine_id', $medicineId)
                ->where('quantity', '>', 0)
                ->whereDate('expiry_date', '>=', today())
                ->orderBy('expiry_date')->orderBy('id')
                ->lockForUpdate()->get();

            $available = (int) $batches->sum('quantity');
            if ($available < $n['qty']) {
                $this->fail("Stok {$medicine->name} tidak mencukupi (tersedia {$available}, diminta {$n['qty']}).");
            }

            $remaining = $n['qty'];
            foreach ($batches as $b) {
                if ($remaining <= 0) break;
                $take = min($remaining, $b->quantity);

                $b->decrement('quantity', $take);
                VisitMedicine::create([
                    'visit_id' => $visit->id, 'medicine_batch_id' => $b->id,
                    'quantity' => $take, 'notes' => $n['notes'],
                ]);
                $this->movement($b, 'out', -$take, $by, $visit->id, 'Pemberian obat pada kunjungan');
                $remaining -= $take;
            }
        }
    }

    /** Kembalikan stok dari kunjungan (dipakai saat edit/hapus kunjungan). */
    public function restore(Visit $visit, User $by, string $note = 'Pembatalan/koreksi kunjungan'): void
    {
        $usages = VisitMedicine::where('visit_id', $visit->id)->orderBy('medicine_batch_id')->get();

        foreach ($usages as $u) {
            $batch = MedicineBatch::lockForUpdate()->findOrFail($u->medicine_batch_id);
            $batch->increment('quantity', $u->quantity);
            $this->movement($batch, 'return', $u->quantity, $by, $visit->id, $note);
            $u->delete();
        }
    }

    public function addBatchStock(MedicineBatch $batch, int $qty, User $by, string $type = 'in', ?string $note = null): void
    {
        $batch = MedicineBatch::lockForUpdate()->findOrFail($batch->id);
        $batch->increment('quantity', $qty);
        $this->movement($batch, $type, $qty, $by, null, $note);
    }

    /** Koreksi stok: set jumlah baru batch, selisih dicatat sebagai mutasi 'adjustment'. */
    public function adjust(MedicineBatch $batch, int $newQty, User $by, string $note): void
    {
        $batch = MedicineBatch::lockForUpdate()->findOrFail($batch->id);
        $delta = $newQty - $batch->quantity;
        if ($delta === 0) return;

        $batch->update(['quantity' => $newQty]);
        $this->movement($batch, 'adjustment', $delta, $by, null, $note);
    }

    public function movement(MedicineBatch $b, string $type, int $delta, User $by, ?int $visitId, ?string $note): void
    {
        MedicineStockMovement::create([
            'medicine_batch_id' => $b->id, 'type' => $type, 'quantity' => $delta,
            'balance_after' => $b->fresh()->quantity, 'visit_id' => $visitId,
            'user_id' => $by->id, 'notes' => $note,
        ]);
    }

    private function fail(string $msg): never
    {
        throw ValidationException::withMessages(['medicines' => $msg]);
    }
}