<?php

use App\Models\User;
use App\Notifications\WelcomeNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

test('creates a user', function () {
    Notification::fake();

    $payload = [
        'name'                  => 'Maria Teste',
        'email'                 => fake()->unique()->safeEmail(),
        'cpf'                   => fake()->unique()->numerify('###########'),
        'age'                   => 25,
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ];

    $response = $this->postJson('/api/v1/users', $payload);

    $response
        ->assertCreated()
        ->assertJsonPath('data.email', $payload['email']);

    $user = User::where('email', $payload['email'])->first();

    $this->assertDatabaseHas('users', [
        'email' => $payload['email'],
        'cpf'   => $payload['cpf'],
    ]);

    Notification::assertSentTo($user, WelcomeNotification::class);
});

test('lists users with pagination', function () {
    User::factory()->count(3)->create();

    $response = $this->getJson('/api/v1/users');

    $response
        ->assertSuccessful()
        ->assertJsonStructure([
            'data',
            'links' => ['first', 'last', 'prev', 'next'],
            'meta'  => ['current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total'],
        ]);
});

test('shows a user', function () {
    $user = User::factory()->create();

    $response = $this->getJson("/api/v1/users/{$user->id}");

    $response
        ->assertSuccessful()
        ->assertJsonPath('data.id', $user->id);
});

test('updates a user', function () {
    $user = User::factory()->create();

    $payload = [
        'name'  => 'Novo Nome',
        'email' => fake()->unique()->safeEmail(),
        'cpf'   => fake()->unique()->numerify('###########'),
        'age'   => 30,
    ];

    $response = $this->putJson("/api/v1/users/{$user->id}", $payload);

    $response
        ->assertSuccessful()
        ->assertJsonPath('data.email', $payload['email']);

    $this->assertDatabaseHas('users', [
        'id'    => $user->id,
        'email' => $payload['email'],
        'cpf'   => $payload['cpf'],
    ]);
});

test('deletes a user', function () {
    $user = User::factory()->create();

    $response = $this->deleteJson("/api/v1/users/{$user->id}");

    $response->assertNoContent();

    $this->assertSoftDeleted('users', ['id' => $user->id]);
});

test('fails to create a user when required fields are missing', function (string $field) {
    $payload = [
        'name'                  => 'Maria Teste',
        'email'                 => fake()->unique()->safeEmail(),
        'cpf'                   => fake()->unique()->numerify('###########'),
        'age'                   => 25,
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ];

    unset($payload[$field]);

    $response = $this->postJson('/api/v1/users', $payload);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors($field);
})->with([
    'name',
    'email',
    'cpf',
    'age',
    'password',
]);

test('fails to create a user when email is not unique', function () {
    $existing = User::factory()->create();

    $payload = [
        'name'                  => 'Maria Teste',
        'email'                 => $existing->email,
        'cpf'                   => fake()->unique()->numerify('###########'),
        'age'                   => 25,
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ];

    $this->postJson('/api/v1/users', $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors('email');
});

test('fails to create a user when cpf is not unique', function () {
    $existing = User::factory()->create();

    $payload = [
        'name'                  => 'Maria Teste',
        'email'                 => fake()->unique()->safeEmail(),
        'cpf'                   => $existing->cpf,
        'age'                   => 25,
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ];

    $this->postJson('/api/v1/users', $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors('cpf');
});

test('fails to create a user when password confirmation does not match', function () {
    $payload = [
        'name'                  => 'Maria Teste',
        'email'                 => fake()->unique()->safeEmail(),
        'cpf'                   => fake()->unique()->numerify('###########'),
        'age'                   => 25,
        'password'              => 'password123',
        'password_confirmation' => 'wrong',
    ];

    $this->postJson('/api/v1/users', $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors('password');
});

test('fails to create a user when age is below minimum', function () {
    $payload = [
        'name'                  => 'Maria Teste',
        'email'                 => fake()->unique()->safeEmail(),
        'cpf'                   => fake()->unique()->numerify('###########'),
        'age'                   => 17,
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ];

    $this->postJson('/api/v1/users', $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors('age');
});

test('fails to update a user when email is not unique', function () {
    $user  = User::factory()->create();
    $other = User::factory()->create();

    $payload = [
        'email' => $other->email,
    ];

    $this->putJson("/api/v1/users/{$user->id}", $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors('email');
});

test('fails to update a user when cpf is not unique', function () {
    $user  = User::factory()->create();
    $other = User::factory()->create();

    $payload = [
        'cpf' => $other->cpf,
    ];

    $this->putJson("/api/v1/users/{$user->id}", $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors('cpf');
});

test('returns redirect when accept header is not json', function () {
    $payload = [
        'name'                  => 'Maria Teste',
        'email'                 => fake()->unique()->safeEmail(),
        'cpf'                   => fake()->unique()->numerify('###########'),
        'age'                   => 25,
        'password'              => 'password123',
        'password_confirmation' => 'password123',
    ];

    $response = $this->post('/api/v1/users', $payload, ['Content-Type' => 'application/json']);

    $response->assertRedirect('/');
});
