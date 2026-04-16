<?php

test('returns a successful response', function () {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('Laravel API Server');
});
