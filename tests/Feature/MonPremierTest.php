<?php

it('has monpremier page', function () {
    $response = $this->get('/monpremier');

    $response->assertStatus(200);
});
