<?php

test('registration screen is disabled for public users', function () {
    $response = $this->get('/register');

    $response->assertNotFound();
});

test('public registration post endpoint is disabled', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertNotFound();
});
