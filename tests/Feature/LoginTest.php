<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_login_success()
    {

        $user = User::factory()->create([
            'email' => 'admin@admin.com',
            'admin' => 1,
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/auth', [
            'email' => 'admin@admin.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure ([
            "message",
            "user" => ["id","admin","email","name"],

        ]);
    }

    public function test_login_fail()
    {

        $user = User::factory()->create([
            'email' => 'admin@admin.com',
            'admin' => 1,
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/auth', [
            'email' => 'admin@admin.com',
            'password' => 'invalidPassword',
        ]);


        $response->assertStatus(401);
        $response->assertJsonFragment ([
            "message" => "Email and/or Password are incorrect!"

        ]);

    }
}
