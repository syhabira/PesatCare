<?php

it('redirects root to the login page', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('login'));
});
