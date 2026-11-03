<?php

namespace Database\Seeders;

use App\Models\Disease;
use App\Models\MedicalCertificate;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Unit;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Database\Seeder;

class DemoAccountSeeder extends Seeder
{
    /**
     * Seeder akun demo guru, guru piket, dan murid untuk keperluan pengembangan & presentasi.
     *
     * Akun:
     *   Guru       – username: guru       | NIP: 198001012005011001 | password: password
     *   Guru Piket – username: gurupiket  | NIP: 198502022010012002 | password: password
     *   Murid      – username: murid      | NIS: 20241001           | password: password
     */
    public function run(): void
    {
        // Pastikan data pendukung ada
        $unit = Unit::firstOrCreate(
            ['unit_name' => 'Guru Mata Pelajaran'],
            ['is_active' => true]
        );

        $unitPiket = Unit::firstOrCreate(
            ['unit_name' => 'Tim Piket & Ketertiban'],
            ['is_active' => true]
        );

        $class = SchoolClass::firstOrCreate(
            ['class_name' => 'XII RPL 1', 'academic_year' => '2026/2027'],
            ['grade_level' => 12, 'school_level' => 'SMK', 'is_active' => true]
        );

        $class2 = SchoolClass::firstOrCreate(
            ['class_name' => 'XI TKJ 2', 'academic_year' => '2026/2027'],
            ['grade_level' => 11, 'school_level' => 'SMK', 'is_active' => true]
        );

        // ── AKUN GURU BIASA ────────────────────────────────────────────────
        $guruUser = User::firstOrCreate(
            ['username' => 'guru'],
            [
                'name' => 'Budi Santoso, S.Pd.',
                'email' => 'guru@pesatcare.sch.id',
                'password' => 'password',
                'role' => User::ROLE_GURU,
                'status' => 'active',
            ]
        );

        Teacher::firstOrCreate(
            ['user_id' => $guruUser->id],
            [
                'nip' => '198001012005011001',
                'name' => $guruUser->name,
                'unit_id' => $unit->id,
                'lp_number' => 'LP-001',
                'blood_type' => 'O',
                'is_active' => true,
            ]
        );

        // ── AKUN GURU PIKET ────────────────────────────────────────────────
        $piketUser = User::firstOrCreate(
            ['username' => 'gurupiket'],
            [
                'name' => 'Dra. Siti Rahmawati (Guru Piket)',
                'email' => 'piket@pesatcare.sch.id',
                'password' => 'password',
                'role' => User::ROLE_GURU,
                'status' => 'active',
            ]
        );

        Teacher::firstOrCreate(
            ['user_id' => $piketUser->id],
            [
                'nip' => '198502022010012002',
                'name' => $piketUser->name,
                'unit_id' => $unitPiket->id,
                'lp_number' => 'LP-PIKET-01',
                'blood_type' => 'A',
                'is_active' => true,
            ]
        );

        // ── AKUN MURID 1 ───────────────────────────────────────────────────
        $muridUser = User::firstOrCreate(
            ['username' => 'murid'],
            [
                'name' => 'Ahmad Fauzan',
                'email' => 'murid@pesatcare.sch.id',
                'password' => 'password',
                'role' => User::ROLE_MURID,
                'status' => 'active',
            ]
        );

        $student1 = Student::firstOrCreate(
            ['user_id' => $muridUser->id],
            [
                'nis' => '20241001',
                'name' => $muridUser->name,
                'class_id' => $class->id,
                'gender' => 'L',
                'birth_date' => '2008-05-15',
                'is_active' => true,
            ]
        );

        // ── AKUN MURID 2 (Contoh untuk data sakit variatif) ────────────────
        $murid2User = User::firstOrCreate(
            ['username' => 'nadia'],
            [
                'name' => 'Nadia Putri',
                'email' => 'nadia@pesatcare.sch.id',
                'password' => 'password',
                'role' => User::ROLE_MURID,
                'status' => 'active',
            ]
        );

        $student2 = Student::firstOrCreate(
            ['user_id' => $murid2User->id],
            [
                'nis' => '20241002',
                'name' => $murid2User->name,
                'class_id' => $class2->id,
                'gender' => 'P',
                'birth_date' => '2009-02-10',
                'is_active' => true,
            ]
        );

        // ── DATA SAMPLE KUNJUNGAN HARI INI BIAR GURU PIKET LANGSUNG LIAT ───
        $admin = User::where('role', 'admin')->first() ?? $guruUser;
        $demam = Disease::firstOrCreate(['name' => 'Demam']);
        $pusing = Disease::firstOrCreate(['name' => 'Pusing']);

        // Siswa 1: Sedang istirahat di UKS
        $visit1 = Visit::firstOrCreate(
            [
                'student_id' => $student1->id,
                'visited_at' => today()->setTime(8, 30),
            ],
            [
                'class_id' => $student1->class_id,
                'recorded_by' => $admin->id,
                'complaint' => 'Kepala pusing dan mual setelah upacara',
                'temperature' => 37.8,
                'action' => 'Istirahat di bed UKS 2, diberikan Paracetamol dan teh hangat',
                'notes' => 'Estimasi kembali ke kelas pukul 10:30 WIB jika sudah membaik',
                'triage_level' => 'kuning',
                'left_at' => null,
            ]
        );
        $visit1->diseases()->syncWithoutDetaching([$pusing->id]);

        // Siswa 2: Sudah diizinkan pulang
        $visit2 = Visit::firstOrCreate(
            [
                'student_id' => $student2->id,
                'visited_at' => today()->setTime(9, 15),
            ],
            [
                'class_id' => $student2->class_id,
                'recorded_by' => $admin->id,
                'complaint' => 'Demam tinggi dan lemas',
                'temperature' => 38.6,
                'action' => 'Diperiksa suhu dan tensi, orang tua dihubungi untuk penjemputan',
                'notes' => 'Siswa dipulangkan bersama wali',
                'triage_level' => 'kuning',
                'left_at' => today()->setTime(10, 0),
            ]
        );
        $visit2->diseases()->syncWithoutDetaching([$demam->id]);

        MedicalCertificate::firstOrCreate(
            ['visit_id' => $visit2->id],
            [
                'letter_number' => 'UKS/SK/'.date('Ym').'/001',
                'status_home' => true,
                'departure_time' => today()->setTime(10, 0),
                'additional_notes' => 'Dijemput orang tua pada pukul 10:00 WIB',
                'created_by' => $admin->id,
            ]
        );

        $this->command->info('Akun demo & data monitoring piket berhasil dibuat:');
        $this->command->table(
            ['Role', 'Username', 'Password', 'Keterangan'],
            [
                ['Admin',      'shifa / mela', 'password', 'Akun admin UKS'],
                ['Guru Piket', 'gurupiket',    'password', "NIP: 198502022010012002 | {$piketUser->name}"],
                ['Guru Biasa', 'guru',         'password', "NIP: 198001012005011001 | {$guruUser->name}"],
                ['Murid',      'murid',        'password', "NIS: 20241001 | {$student1->name}"],
            ]
        );
    }
}
