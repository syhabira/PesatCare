<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Support\Carbon;

class ScreeningService
{
    public function __construct(private BmiService $bmi) {}

    /** Hitung semua field turunan di server. Input BMI dari user diabaikan. */
    public function compute(Student $s, array $d): array
    {
        $date = Carbon::parse($d['screened_at']);
        $age = $s->birth_date ? (int) $s->birth_date->diffInMonths($date) : null;
        $bmi = $this->bmi->bmi((float) $d['height_cm'], (float) $d['weight_kg']);
        $cls = $this->bmi->classify($s->gender, $age, $bmi);

        return [
            'student_id' => $s->id,
            'class_id' => $s->class_id,
            'screened_at' => $date->toDateString(),
            'age_months' => $age,
            'height_cm' => $d['height_cm'],
            'weight_kg' => $d['weight_kg'],
            'bmi' => $bmi,
            'weight_indicator_code' => $cls['code'],
            'weight_indicator' => $cls['label'],
            'waist_cm' => $d['waist_cm'] ?? null,
            'waist_category' => $this->waist($s->gender, $d['waist_cm'] ?? null),
            'systolic' => $d['systolic'] ?? null,
            'diastolic' => $d['diastolic'] ?? null,
            'blood_pressure_category' => $this->bp($d['systolic'] ?? null, $d['diastolic'] ?? null),
        ];
    }

    private function waist(string $sex, $cm): ?string
    {
        if ($cm === null) return null;
        $max = config("uks.waist_max_cm.$sex");
        if ($max === null) return 'Belum ada acuan';
        return $cm > $max ? 'Di atas batas rujukan' : 'Dalam rentang rujukan';
    }

    private function bp($sys, $dia): ?string
    {
        if ($sys === null || $dia === null) return null;
        $c = config('uks.blood_pressure');
        if ($c['systolic_high'] === null || $c['diastolic_high'] === null) return 'Belum ada acuan';

        if ($sys >= $c['systolic_high'] || $dia >= $c['diastolic_high']) return 'Di atas batas rujukan';
        if (($c['systolic_low'] !== null && $sys < $c['systolic_low'])
            || ($c['diastolic_low'] !== null && $dia < $c['diastolic_low'])) return 'Di bawah batas rujukan';
        return 'Dalam rentang rujukan';
    }
}