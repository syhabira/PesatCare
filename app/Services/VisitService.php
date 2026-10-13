<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class VisitService
{
    public function __construct(private TriageService $triage, private StockService $stock) {}

    public function store(array $d, User $by): Visit
    {
        return DB::transaction(function () use ($d, $by) {
            $visit = new Visit();
            $visit->recorded_by = $by->id;
            $this->fill($visit, $d);
            $visit->save();

            $visit->diseases()->sync($d['disease_ids']);
            $this->stock->dispense($visit, $d['medicines'] ?? [], $by);

            return $visit;
        });
    }

    public function update(Visit $visit, array $d, User $by): Visit
    {
        return DB::transaction(function () use ($visit, $d, $by) {
            $visit = Visit::lockForUpdate()->findOrFail($visit->id);

            // Kembalikan stok lama lalu potong ulang -> tidak ada pengurangan ganda
            $this->stock->restore($visit, $by, 'Koreksi kunjungan (edit)');

            $wasHandled = $visit->triage_status === 'handled';
            $visit->updated_by = $by->id;
            $this->fill($visit, $d);
            if ($wasHandled && $visit->triage_status === 'pending') {
                $visit->triage_status = 'handled';
            }
            $visit->save();

            $visit->diseases()->sync($d['disease_ids']);
            $this->stock->dispense($visit, $d['medicines'] ?? [], $by);

            return $visit;
        });
    }

    public function destroy(Visit $visit, User $by): void
    {
        DB::transaction(function () use ($visit, $by) {
            $visit = Visit::lockForUpdate()->findOrFail($visit->id);
            $this->stock->restore($visit, $by, 'Kunjungan dihapus');
            $visit->diseases()->detach();
            $visit->update(['updated_by' => $by->id]);
            $visit->delete(); // soft delete; jejak ada di audit_logs
        });
    }

    public function markTriageHandled(Visit $visit): void
    {
        $visit->update(['triage_status' => 'handled', 'triage_handled_at' => now()]);
    }

    private function fill(Visit $v, array $d): void
    {
        if ($d['patient_type'] === 'murid') {
            $s = Student::findOrFail($d['student_id']);
            $v->student_id = $s->id; $v->teacher_id = null;
            $v->class_id = $s->class_id; $v->unit_id = null;
        } else {
            $t = Teacher::findOrFail($d['teacher_id']);
            $v->teacher_id = $t->id; $v->student_id = null;
            $v->unit_id = $t->unit_id; $v->class_id = null;
        }

        $v->visited_at = isset($d['visited_at']) ? Carbon::parse($d['visited_at']) : ($v->visited_at ?? now());
        $v->temperature = $d['temperature'] ?? null;
        $v->systolic = $d['systolic'] ?? null;
        $v->diastolic = $d['diastolic'] ?? null;
        $v->pulse = $d['pulse'] ?? null;
        $v->complaint = $d['complaint'];
        $v->anamnesis = $d['anamnesis'] ?? null;
        $v->action = $d['action'] ?? null;
        $v->notes = $d['notes'] ?? null;
        $v->left_at = $d['left_at'] ?? null;
        $v->follow_up_status = $d['follow_up_status'] ?? null;

        $r = $this->triage->evaluate($d);
        $v->triage_level = $r['level'];
        $v->triage_reason = $r['reason'];
        $v->triage_snapshot = $r['snapshot'];
        $v->triage_deadline = $r['deadline_minutes'] ? $v->visited_at->copy()->addMinutes($r['deadline_minutes']) : null;
        $v->triage_status = $r['deadline_minutes'] ? 'pending' : 'not_required';
    }
}