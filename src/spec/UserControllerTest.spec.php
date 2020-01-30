<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

describe('User API', function() {
    beforeAll(function() {
        $this->laravel->useTrait(RefreshDatabase::class);
    });

    context('Index', function() {
        it('status code 200', function() {
            $response = $this->laravel->get('/api/user');

            expect($response)->toPassStatus(200);
        });
    });

    context('Show', function() {
        it('status code 200', function() {
            $id = 1;
            $response = $this->laravel->get("/api/user/{$id}");

            expect($response)->toPassStatus(200);
        });
    });

    context('Store', function() {
        beforeEach(function() {
            $this->data = [
                "name" => "田中太郎",
                "email" => "test@example.com",
                "password" => "123456789"
            ];
        });

        it('status code 200', function() {
            $response = $this->laravel->post('/api/user', $this->data);

            expect($response)->toPassStatus(200);
        });

        it('normal response', function() {
            $response = $this->laravel->post('/api/user', $this->data);

            expect($response)->toPassJsonFragment([
                'name' => $this->data['name'],
                'email' => $this->data['email']
            ]);
        });

        it('stored in db', function() {
            $response = $this->laravel->post('/api/user', $this->data);

            expect($this->laravel)->toPassDatabaseHas('users', $this->data);
        });
    });

    context('Update', function() {
        beforeEach(function() {
            $this->user = factory(User::class)->create();
            $this->data = [
                "name" => "田中太郎",
                "email" => "test@example.com",
                "password" => "123456789"
            ];
        });

        it('status code 200', function() {
            $response = $this->laravel->put("/api/user/{$this->user->id}", $this->data);

            expect($response)->toPassStatus(200);
        });

        it('normal response', function() {
            $response = $this->laravel->put("/api/user/{$this->user->id}", $this->data);

            expect($response)->toPassJsonFragment([
                'name' => $this->data['name'],
                'email' => $this->data['email']
            ]);
        });

        it('data is updated', function() {
            $response = $this->laravel->put("/api/user/{$this->user->id}", $this->data);

            expect($this->laravel)->toPassDatabaseHas('users', $this->data);
        });
    });

    context('Delete', function() {
        beforeEach(function() {
            $this->user = factory(User::class)->create();
            $this->data = [
                "name" => "田中太郎",
                "email" => "test@example.com",
                "password" => "123456789"
            ];
        });

        it('status code 200', function() {
            $response = $this->laravel->delete("/api/user/{$this->user->id}");

            expect($response)->toPassStatus(200);
        });

        it('data is deleted', function() {
            $response = $this->laravel->delete("/api/user/{$this->user->id}");

            expect($this->laravel)->toPassSoftDeleted('users', [
                'name' => $this->user->name,
                'email' => $this->user->email
            ]);
        });
    });
});