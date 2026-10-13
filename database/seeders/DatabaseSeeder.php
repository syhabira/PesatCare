<?php

namespace Database\Seeders;

use App\Models\Disease;
use App\Models\TriageRule;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun petugas. Password dari .env (SEED_ADMIN_PASSWORD) atau acak lalu ditampilkan sekali.
        $password = env('SEED_ADMIN_PASSWORD') ?: Str::password(14, symbols: false);
        foreach (['Bu Shifa' => 'shifa', 'Bu Mela' => 'mela'] as $name => $username) {
            User::firstOrCreate(['username' => $username], ['name' => $name, 'password' => $password, 'role' => 'admin']);
        }
        $this->command->warn("Password akun admin (shifa & mela): {$password}");

        foreach (['Demam', 'Batuk', 'Flu', 'Sakit Kepala', 'Pusing', 'Nyeri Perut', 'Luka Ringan'] as $d) {
            Disease::firstOrCreate(['name' => $d]);
        }
        Unit::firstOrCreate(['unit_name' => 'Guru Mata Pelajaran']);

        /*
         * PLACEHOLDER ATURAN TRIASE: BUKAN STANDAR MEDIS RESMI.
         * Nilai ini hanya agar fitur dapat dicoba. Semua ditandai is_validated=false.
         * Wajib diganti/disahkan sesuai SOP UKS lewat menu Aturan Triase.
         */
        $rules = [
            ['Suhu tinggi (placeholder)', 'temperature', 'gte', 38.0, null, 'kuning', 'suhu mencapai batas perhatian', 60],
            ['Suhu sangat tinggi (placeholder)', 'temperature', 'gte', 39.0, null, 'merah', 'suhu tinggi', 15],
            ['Sistolik tinggi (placeholder)', 'systolic', 'gte', 140, null, 'kuning', 'tekanan darah sistolik di luar batas rujukan', 60],
            ['Sistolik sangat tinggi (placeholder)', 'systolic', 'gte', 160, null, 'merah', 'tekanan darah di luar batas rujukan', 15],
        ];
        if (TriageRule::count() === 0) {
            foreach ($rules as [$name, $param, $op, $val, $max, $level, $msg, $deadline]) {
                TriageRule::create([
                    'name' => $name, 'parameter' => $param, 'operator' => $op, 'value' => $val, 'value_max' => $max,
                    'level' => $level, 'message' => $msg, 'deadline_minutes' => $deadline,
                    'is_active' => true, 'is_validated' => false,
                ]);
            }
        }

        $this->importBmiReference();
    }

    /** CSV: header `sex,age_months,l,m,s` (sex = L/P). Taruh di database/data/bmi_for_age.csv. */
    private function importBmiReference(): void
    {
        $file = database_path('data/bmi_for_age.csv');
        if (! is_file($file)) {
            $this->command->warn('database/data/bmi_for_age.csv tidak ditemukan. Indikator BMI akan tampil "Belum ada acuan".');
            return;
        }

        $h = fopen($file, 'r');
        fgetcsv($h);
        $rows = [];
        while (($r = fgetcsv($h)) !== false) {
            $rows[] = ['sex' => $r[0], 'age_months' => (int) $r[1], 'l' => $r[2], 'm' => $r[3], 's' => $r[4]];
        }
        fclose($h);

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('bmi_references')->upsert($chunk, ['sex', 'age_months'], ['l', 'm', 's']);
        }
    }
}