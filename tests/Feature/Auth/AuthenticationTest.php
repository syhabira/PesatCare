<?php

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using their email', function () {
    $user = User::factory()->create([
        'password' => 'password',
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route($user->homeRoute()));
});

test('users can authenticate using their username', function () {
    $user = User::factory()->create([
        'username' => 'petugas_uks',
        'password' => 'password',
    ]);

    $response = $this->post('/login', [
        'login' => 'petugas_uks',
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route($user->homeRoute()));
});

test('users can authenticate using their nip', function () {
    $user = User::factory()->create([
        'role' => User::ROLE_GURU,
        'password' => 'password',
    ]);

    Teacher::create([
        'user_id' => $user->id,
        'nip' => '198501012010012001',
        'name' => $user->name,
    ]);

    $response = $this->post('/login', [
        'login' => '198501012010012001',
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route($user->homeRoute()));
});

test('users can authenticate using their nis', function () {
    $class = SchoolClass::create(['class_name' => 'X RPL 1', 'grade_level' => 10, 'academic_year' => '2026/2027']);
    $user = User::factory()->create([
        'role' => User::ROLE_MURID,
        'password' => 'password',
    ]);

    Student::create([
        'user_id' => $user->id,
        'nis' => '12345678',
        'name' => $user->name,
        'class_id' => $class->id,
        'gender' => 'L',
        'birth_date' => '2008-01-01',
    ]);

    $response = $this->post('/login', [
        'login' => '12345678',
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route($user->homeRoute()));
});

test('users can not authenticate with unregistered nip', function () {
    $this->post('/login', [
        'login' => '999999999999999999',
        'password' => 'password',
    ]);

    $this->assertGuest();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'login' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect(route('login'));
});
