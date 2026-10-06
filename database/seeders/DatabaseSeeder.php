<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin UKS (Penjaga UKS: Ibu Shifa & Ibu Mela)
        User::updateOrCreate(
            ['email' => 'shifa@pesatcare.sch.id'],
            [
                'name' => 'Ibu Shifa (Penjaga UKS)',
                'nip' => '198501012010012001',
                'role' => 'admin',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'mela@pesatcare.sch.id'],
            [
                'name' => 'Ibu Mela (Penjaga UKS)',
                'nip' => '198802152012022002',
                'role' => 'admin',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );

        // 2. Akun Guru
        User::updateOrCreate(
            ['email' => 'guru@pesatcare.sch.id'],
            [
                'name' => 'Bapak Ahmad, S.Pd (Guru)',
                'nip' => '197905142005011003',
                'role' => 'guru',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );

        // 3. Akun Murid
        User::updateOrCreate(
            ['email' => 'siswa@pesatcare.sch.id'],
            [
                'name' => 'Ahmad Siswa (Murid)',
                'nip' => '20241001',
                'role' => 'murid',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );

        // 4. Default Test User untuk kompatibilitas test suite
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'nip' => '199999999999999999',
                'role' => 'admin',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );
    }
}
