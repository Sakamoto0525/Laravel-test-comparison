<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function indexAPI()
    {
        $response = $this->get('/api/user');

        $response->assertStatus(200);
    }

    /** @test */
    public function showAPI()
    {
        $id = 1;
        $response = $this->get("/api/user/{$id}");

        $response->assertStatus(200);
    }

    /** @test */
    public function storeAPI()
    {
        $data = [
            "name" => "坂本雅也",
            "email" => "test@example.com",
            "password" => "123456789"
        ];

        $response = $this->post('/api/user', $data);

        $response->assertStatus(200)
        ->assertJsonFragment([
            'email' => 'test@example.com',
        ]);
        $this->assertDatabaseHas('users', $data);
    }

    /** @test */
    public function updateAPI()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        // $makUser = factory(User::class)->make();

        $id = 1;
        $data = [
            "name" => "坂本雅也",
            "email" => "test@example.com",
            "password" => "password"
        ];
        $response = $this->put("/api/user/{$user->id}", $data);

        $response->assertStatus(200)
        ->assertJsonFragment([
            "name" => "坂本雅也",
            "email" => "test@example.com"
        ]);
        $this->assertDatabaseHas('users', $data);
    }

    /** @test */
    public function deleteAPI()
    {
        $user = factory(User::class)->create();

        $response = $this->DELETE("/api/user/{$user->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('users', [
            'name' => $user->name,
            'email' => $user->email
        ]);
    }
}
