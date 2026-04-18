<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase; // <--- Add this line
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase; // <--- Add this line inside the class

    /**
     * A basic feature test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}