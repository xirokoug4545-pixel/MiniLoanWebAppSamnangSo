<?php

it('redirects the root route to the login screen', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('login', absolute: false));
});
