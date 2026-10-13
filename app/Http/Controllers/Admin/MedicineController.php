<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\MedicineStockMovement;
use App\Models\VisitMedicine;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MedicineController extends Controller
{
    public function __construct(private StockService $stock) {}

    public function index(Request $request)
    {
        $medicines = Medicine::withStock()
            ->when($request->q, fn ($q, $s) => $q->where('name', 'like', "%$s%"))
            ->when($request->filter === 'low', fn ($q) => $q->havingRaw('COALESCE(stock,0) <= low_stock_threshold'))
            ->orderBy('name')->paginate(20)->withQueryString();

        return view('admin.medicines.index', compact('medicines'));
    }

    public function show(Medicine $medicine)
    {
        $medicine->loadSum(['batches as stock' => fn ($b) => $b->whereDate('expiry_date', '>=', today())], 'quantity');
        $batches = $medicine->batches()->orderBy('expiry_date')->get();

        $movements = MedicineStockMovement::with(['batch:id,batch_number', 'user:id,name', 'visit:id'])
            ->whereIn('medicine_batch_id', $batches->pluck('id'))
            ->latest('id')->paginate(25);

        $usedTotal = VisitMedicine::whereIn('medicine_batch_id', $batches->pluck('id'))->sum('quantity');

        return view('admin.medicines.show', compact('medicine', 'batches', 'movements', 'usedTotal'));
    }

    public function store(Request $request)
    {
        $d = $request->validate([
            'name' => 'required|string|max:150|unique:medicines,name',
            'indication' => 'nullable|string|max:500',
            'medicine_class' => 'nullable|string|max:50',
            'unit' => 'required|string|max:30',
            'low_stock_threshold' => 'required|integer|min:0',
            'symbol' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:512',
            // stok awal (opsional)
            'batch_number' => 'nullable|required_with:quantity|string|max:50',
            'quantity' => 'nullable|required_with:batch_number|integer|min:1',
            'expiry_date' => 'nullable|required_with:quantity|date|after:today',
        ]);

        DB::transaction(function () use ($request, $d) {
            $m = Medicine::create([
                ...collect($d)->only(['name', 'indication', 'medicine_class', 'unit', 'low_stock_threshold'])->all(),
                'symbol' => $request->file('symbol')?->store('medicine-symbols', 'public'),
            ]);

            if (! empty($d['quantity'])) {
                $b = $m->batches()->create(['batch_number' => $d['batch_number'], 'quantity' => 0, 'expiry_date' => $d['expiry_date']]);
                $this->stock->addBatchStock($b, (int) $d['quantity'], $request->user(), 'initial', 'Stok awal');
            }
        });

        return redirect()->route('admin.medicines.index')->with('success', 'Obat ditambahkan.');
    }

    public function update(Request $request, Medicine $medicine)
    {
        $d = $request->validate([
            'name' => ['required', 'string', 'max:150', Rule::unique('medicines', 'name')->ignore($medicine->id)],
            'indication' => 'nullable|string|max:500',
            'medicine_class' => 'nullable|string|max:50',
            'unit' => 'required|string|max:30',
            'low_stock_threshold' => 'required|integer|min:0',
            'symbol' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:512',
        ]);

        if ($request->hasFile('symbol')) {
            if ($medicine->symbol) Storage::disk('public')->delete($medicine->symbol);
            $d['symbol'] = $request->file('symbol')->store('medicine-symbols', 'public');
        } else {
            unset($d['symbol']);
        }

        $medicine->update($d);
        return back()->with('success', 'Obat diperbarui.');
    }

    public function toggle(Medicine $medicine)
    {
        $medicine->update(['is_active' => ! $medicine->is_active]);
        return back()->with('success', 'Status obat diubah.');
    }

    /** Tambah stok: batch baru atau tambah ke batch yang sudah ada (nomor batch sama). */
    public function addStock(Request $request, Medicine $medicine)
    {
        $d = $request->validate([
            'batch_number' => 'required|string|max:50',
            'quantity' => 'required|integer|min:1|max:100000',
            'expiry_date' => 'required|date|after:today',
            'notes' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($d, $medicine, $request) {
            $b = MedicineBatch::firstOrCreate(
                ['medicine_id' => $medicine->id, 'batch_number' => $d['batch_number']],
                ['quantity' => 0, 'expiry_date' => $d['expiry_date']]
            );
            $this->stock->addBatchStock($b, (int) $d['quantity'], $request->user(), 'in', $d['notes'] ?? 'Penambahan stok');
        });

        return back()->with('success', 'Stok ditambahkan.');
    }

    /** Koreksi stok batch (wajib alasan), tercatat sebagai mutasi 'adjustment'. */
    public function adjustStock(Request $request, MedicineBatch $batch)
    {
        $d = $request->validate(['quantity' => 'required|integer|min:0|max:100000', 'notes' => 'required|string|max:255']);

        DB::transaction(fn () => $this->stock->adjust($batch, (int) $d['quantity'], $request->user(), $d['notes']));

        return back()->with('success', 'Stok dikoreksi.');
    }
}