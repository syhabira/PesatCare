<?php

namespace App\Services;

use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\Student;
use App\Models\Visit;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Definisi metrik (SRS 6.6):
 * - siswa/kelas teraktif: jumlah RECORD kunjungan (kelas memakai snapshot saat kunjungan)
 * - penyakit terbanyak: jumlah relasi visit_diseases
 * - obat terbanyak: SUM(visit_medicines.quantity)
 * Setiap metrik memakai query sendiri agar tidak terjadi hitung ganda akibat JOIN.
 */
class StatisticsService
{
    /** @return array{0:Carbon,1:Carbon} */
    public function range(array $in): array
    {
        $now = now();
        return match ($in['period'] ?? 'month') {
            'today' => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
            'week' => [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()],
            'custom' => [Carbon::parse($in['from'])->startOfDay(), Carbon::parse($in['to'])->endOfDay()],
            default => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
        };
    }

    public function dashboard(Carbon $from, Carbon $to): array
    {
        return [
            'visits_today' => Visit::whereDate('visited_at', today())->count(),
            'visits_month' => Visit::whereBetween('visited_at', [now()->startOfMonth(), now()->endOfMonth()])->count(),
            'visits_period' => $this->visits($from, $to)->count(),
            'total_students' => Student::active()->count(),
            'total_medicines' => Medicine::active()->count(),
            'screening_count' => $this->screenings($from, $to)->count(),
            'screened_students' => $this->screenings($from, $to)->distinct('student_id')->count('student_id'),
            'overdue_triage' => Visit::overdue()->count(),
            'chart' => $this->visitsPerDay($from, $to),
            'top_diseases' => $this->topDiseases($from, $to, 5),
            'top_classes' => $this->topClasses($from, $to, 5),
            'top_students' => $this->topStudents($from, $to, 5),
            'top_medicines' => $this->topMedicines($from, $to, 5),
            'low_stock' => $this->lowStock(),
            'expiring' => $this->expiringBatches(),
            'weight_indicators' => $this->screeningDistribution($from, $to, 'weight_indicator'),
            'bp_categories' => $this->screeningDistribution($from, $to, 'blood_pressure_category'),
            'overdue_visits' => Visit::overdue()->with(['student:id,name', 'teacher:id,name'])->orderBy('triage_deadline')->limit(10)->get(),
        ];
    }

    public function report(Carbon $from, Carbon $to): array
    {
        return [
            'top_students' => $this->topStudents($from, $to, 20),
            'top_diseases' => $this->topDiseases($from, $to, 20),
            'top_classes' => $this->topClasses($from, $to, 20),
            'top_medicines' => $this->topMedicines($from, $to, 20),
            'screening_count' => $this->screenings($from, $to)->count(),
            'screened_students' => $this->screenings($from, $to)->distinct('student_id')->count('student_id'),
            'weight_indicators' => $this->screeningDistribution($from, $to, 'weight_indicator'),
            'bp_categories' => $this->screeningDistribution($from, $to, 'blood_pressure_category'),
            'chart' => $this->visitsPerDay($from, $to),
        ];
    }

    private function visits($from, $to)
    {
        return Visit::whereBetween('visited_at', [$from, $to]);
    }

    private function screenings($from, $to)
    {
        return DB::table('screenings')->whereBetween('screened_at', [$from->toDateString(), $to->toDateString()]);
    }

    private function baseVisits($from, $to)
    {
        return DB::table('visits')->whereNull('visits.deleted_at')->whereBetween('visits.visited_at', [$from, $to]);
    }

    public function visitsPerDay($from, $to)
    {
        return $this->baseVisits($from, $to)
            ->selectRaw('DATE(visits.visited_at) as day, COUNT(*) as total')
            ->groupBy('day')->orderBy('day')->get();
    }

    public function topDiseases($from, $to, int $limit)
    {
        return $this->baseVisits($from, $to)
            ->join('visit_diseases', 'visit_diseases.visit_id', '=', 'visits.id')
            ->join('diseases', 'diseases.id', '=', 'visit_diseases.disease_id')
            ->groupBy('diseases.id', 'diseases.name')
            ->selectRaw('diseases.name, COUNT(*) as total')
            ->orderByDesc('total')->limit($limit)->get();
    }

    public function topClasses($from, $to, int $limit)
    {
        return $this->baseVisits($from, $to)
            ->whereNotNull('visits.student_id')
            ->join('classes', 'classes.id', '=', 'visits.class_id')
            ->groupBy('classes.id', 'classes.class_name')
            ->selectRaw('classes.class_name, COUNT(*) as total')
            ->orderByDesc('total')->limit($limit)->get();
    }

    public function topStudents($from, $to, int $limit)
    {
        return $this->baseVisits($from, $to)
            ->whereNotNull('visits.student_id')
            ->join('students', 'students.id', '=', 'visits.student_id')
            ->leftJoin('classes', 'classes.id', '=', 'students.class_id')
            ->groupBy('students.id', 'students.name', 'classes.class_name')
            ->selectRaw('students.name, classes.class_name, COUNT(*) as total')
            ->orderByDesc('total')->limit($limit)->get();
    }

    public function topMedicines($from, $to, int $limit)
    {
        return $this->baseVisits($from, $to)
            ->join('visit_medicines', 'visit_medicines.visit_id', '=', 'visits.id')
            ->join('medicine_batches', 'medicine_batches.id', '=', 'visit_medicines.medicine_batch_id')
            ->join('medicines', 'medicines.id', '=', 'medicine_batches.medicine_id')
            ->groupBy('medicines.id', 'medicines.name', 'medicines.unit')
            ->selectRaw('medicines.name, medicines.unit, SUM(visit_medicines.quantity) as total')
            ->orderByDesc('total')->limit($limit)->get();
    }

    public function lowStock()
    {
        $today = today()->toDateString();

        return Medicine::active()->withStock()
            ->whereRaw('
                COALESCE((
                    SELECT SUM(quantity) 
                    FROM medicine_batches 
                    WHERE medicine_batches.medicine_id = medicines.id 
                    AND DATE(expiry_date) >= ?
                ), 0) <= low_stock_threshold
            ', [$today])
            ->orderBy('name')
            ->get();
    }

    public function expiringBatches()
    {
        return MedicineBatch::with('medicine:id,name,unit')
            ->where('quantity', '>', 0)
            ->whereDate('expiry_date', '<=', today()->addDays(config('uks.expiry_warning_days')))
            ->orderBy('expiry_date')->get();
    }

    private function screeningDistribution($from, $to, string $column)
    {
        return $this->screenings($from, $to)->whereNotNull($column)
            ->groupBy($column)->selectRaw("$column as label, COUNT(*) as total")
            ->orderByDesc('total')->get();
    }
}