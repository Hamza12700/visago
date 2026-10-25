<?php

test('the about page renders with its key sections', function () {
    $response = $this->get('/about');

    $response
        ->assertOk()
        ->assertSee('Best Immigration &amp; Visa Consultation.', false)
        ->assertSee('Experience Team Members')
        ->assertSee('Immigration Choose Your Country');
});
