<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class BmiService
{
    public function bmi(float $heightCm, float $weightKg): float
    {
        $m = $heightCm / 100;
        return round($weightKg / ($m * $m), 1);
    }

    /**
     * Klasifikasi BMI-for-age memakai tabel LMS (tabel bmi_references) dan z-score.
     * Batas z (-3, -2, +1, +2) mengikuti WHO Growth Reference 5-19 tahun -> PERLU KONFIRMASI sekolah.
     */
    public function classify(string $sex, ?int $ageMonths, float $bmi): array
    {
        if ($ageMonths === null) {
            return $this->r('no_reference', 'Belum ada acuan (tanggal lahir siswa belum diisi)');
        }

        $ref = DB::table('bmi_references')->where('sex', $sex)->where('age_months', $ageMonths)->first();
        if (! $ref) {
            return $this->r('no_reference', 'Belum ada acuan untuk usia/jenis kelamin ini (tabel referensi belum diisi)');
        }

        $L = (float) $ref->l; $M = (float) $ref->m; $S = (float) $ref->s;
        $z = abs($L) < 1e-9 ? log($bmi / $M) / $S : (pow($bmi / $M, $L) - 1) / ($L * $S);
        $z = round($z, 2);

        return match (true) {
            $z < -3 => $this->r('severe_low', 'Indikator berat badan sangat rendah', $z),
            $z < -2 => $this->r('low', 'Indikator berat badan rendah', $z),
            $z <= 1 => $this->r('normal', 'Dalam rentang rujukan', $z),
            $z <= 2 => $this->r('over', 'Indikator berat badan berlebih', $z),
            default => $this->r('obese', 'Indikator obesitas', $z),
        };
    }

    private function r(string $code, string $label, ?float $z = null): array
    {
        return ['code' => $code, 'label' => $label, 'z' => $z];
    }
}