<?php

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;

test('login page can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
    $response->assertSee('PesatCare');
    $response->assertSee('Masuk ke Akun');
});

test('guru can view dashboard and portal pages', function () {
    $user = User::factory()->guru()->create();
    $teacher = Teacher::create([
        'user_id' => $user->id,
        'nip' => '198501012010011001',
        'name' => 'Budi Santoso, S.Pd',
        'blood_type' => 'O',
        'is_active' => true,
    ]);

    $this->actingAs($user)->get(route('guru.dashboard'))->assertStatus(200)->assertSee('Budi Santoso');
    $this->actingAs($user)->get(route('guru.monitoring'))->assertStatus(200)->assertSee('Pemantauan Siswa Sakit');
    $this->actingAs($user)->get(route('guru.health-checks'))->assertStatus(200)->assertSee('Riwayat Pemeriksaan Kesehatan Guru');
    $this->actingAs($user)->get(route('guru.visits'))->assertStatus(200)->assertSee('Riwayat Kunjungan UKS Guru');
    $this->actingAs($user)->get(route('guru.profile'))->assertStatus(200)->assertSee('Profil Tenaga Pendidik');
});

test('murid can view dashboard and portal pages', function () {
    $class = SchoolClass::create([
        'class_name' => 'XII RPL 1',
        'grade_level' => 12,
        'school_level' => 'SMK',
        'academic_year' => '2026/2027',
        'is_active' => true,
    ]);
    $user = User::factory()->murid()->create();
    $student = Student::create([
        'user_id' => $user->id,
        'nis' => '12345678',
        'name' => 'Ahmad Fauzi',
        'class_id' => $class->id,
        'gender' => 'L',
        'birth_date' => '2008-05-14',
        'is_active' => true,
    ]);

    $this->actingAs($user)->get(route('murid.dashboard'))->assertStatus(200)->assertSee('Ahmad Fauzi');
    $this->actingAs($user)->get(route('murid.screenings'))->assertStatus(200)->assertSee('Riwayat Skrining Kesehatan Berkala');
    $this->actingAs($user)->get(route('murid.visits'))->assertStatus(200)->assertSee('Riwayat Kunjungan ke UKS');
    $this->actingAs($user)->get(route('murid.certificates'))->assertStatus(200)->assertSee('Surat Keterangan UKS');
    $this->actingAs($user)->get(route('murid.profile'))->assertStatus(200)->assertSee('Profil Siswa');
});
