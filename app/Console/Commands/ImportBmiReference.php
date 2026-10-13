<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportBmiReference extends Command
{
    protected $signature = 'uks:import-bmi {file=database/data/bmi_for_age.csv : CSV dengan header sex,age_months,l,m,s}';
    protected $description = 'Impor tabel acuan BMI-for-age (LMS) dari CSV yang disetujui sekolah';

    public function handle(): int
    {
        $arg = $this->argument('file');
        $path = is_file($arg) ? $arg : base_path($arg);

        if (! is_file($path)) {
            $this->error("File tidak ditemukan: {$path}");
            return self::FAILURE;
        }

        $h = fopen($path, 'r');
        $header = array_map(fn ($c) => strtolower(trim((string) $c)), fgetcsv($h) ?: []);
        if ($header !== ['sex', 'age_months', 'l', 'm', 's']) {
            $this->error('Header harus persis: sex,age_months,l,m,s');
            return self::FAILURE;
        }

        $rows = [];
        $line = 1;
        while (($r = fgetcsv($h)) !== false) {
            $line++;
            if ($r === [null]) continue; // baris kosong
            $r = array_map('trim', $r);

            $valid = count($r) === 5
                && in_array($r[0], ['L', 'P'], true)
                && ctype_digit($r[1])
                && is_numeric($r[2]) && is_numeric($r[3]) && is_numeric($r[4])
                && (float) $r[3] > 0 && (float) $r[4] > 0;

            if (! $valid) {
                fclose($h);
                $this->error("Baris {$line} tidak valid. Tidak ada data yang diimpor.");
                return self::FAILURE;
            }

            $rows[] = ['sex' => $r[0], 'age_months' => (int) $r[1], 'l' => $r[2], 'm' => $r[3], 's' => $r[4]];
        }
        fclose($h);

        if (! $rows) {
            $this->error('CSV tidak berisi data.');
            return self::FAILURE;
        }

        DB::transaction(function () use ($rows) {
            foreach (array_chunk($rows, 500) as $chunk) {
                DB::table('bmi_references')->upsert($chunk, ['sex', 'age_months'], ['l', 'm', 's']);
            }
        });

        foreach (['L', 'P'] as $sex) {
            $ages = array_column(array_filter($rows, fn ($x) => $x['sex'] === $sex), 'age_months');
            if ($ages) {
                $this->line("{$sex}: " . count($ages) . ' baris, usia ' . min($ages) . '-' . max($ages) . ' bulan');
            }
        }
        $this->info('Impor selesai. Usia di luar rentang ini akan tampil "Belum ada acuan".');

        return self::SUCCESS;
    }
}