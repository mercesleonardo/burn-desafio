<?php

use App\Models\Position;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('creates a position', function () {
    $company = Company::factory()->create();

    $payload = [
        'company_id' => $company->id,
        'title' => 'Desenvolvedor Backend',
        'description' => 'Vaga para desenvolvedor backend',
        'type' => 'clt',
        'salary' => 3000.00,
        'schedule' => '40 horas/semana',
    ];

    $response = $this->postJson('/api/v1/positions', $payload);

    $response
        ->assertCreated()
        ->assertJsonPath('data.title', $payload['title']);

    $position = Position::where('title', $payload['title'])->first();

    $this->assertDatabaseHas('positions', [
        'title' => $payload['title'],
        'type' => $payload['type'],
    ]);
});

test('lists positions with pagination', function () {
    Position::factory()->count(3)->create();

    $response = $this->getJson('/api/v1/positions');

    $response
        ->assertSuccessful()
        ->assertJsonStructure([
            'data',
            'links' => ['first', 'last', 'prev', 'next'],
            'meta' => ['current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total'],
        ]);
});

test('shows a position', function () {
    $position = Position::factory()->create();

    $response = $this->getJson("/api/v1/positions/{$position->id}");

    $response
        ->assertSuccessful()
        ->assertJsonPath('data.id', $position->id);
});

test('updates a position', function () {
    $position = Position::factory()->create();

    $payload = [
        'title' => 'Desenvolvedor Frontend',
    ];

    $response = $this->putJson("/api/v1/positions/{$position->id}", $payload);

    $response
        ->assertSuccessful()
        ->assertJsonPath('data.title', $payload['title']);

    $this->assertDatabaseHas('positions', [
        'id' => $position->id,
        'title' => $payload['title'],
    ]);
});

test('deletes a position', function () {
    $position = Position::factory()->create();

    $response = $this->deleteJson("/api/v1/positions/{$position->id}");

    $response->assertNoContent();

    $this->assertSoftDeleted($position);
});

test('user applies to position', function () {
    $position = Position::factory()->create();
    $user = \App\Models\User::factory()->create();

    $payload = [
        'user_id' => $user->id,
    ];

    $response = $this->postJson("/api/v1/positions/{$position->id}/apply", $payload);

    $response
        ->assertSuccessful()
        ->assertJson(['message' => 'Applied successfully.']);

    $this->assertDatabaseHas('position_user', [
        'user_id' => $user->id,
        'position_id' => $position->id,
    ]);
});

test('user cannot apply twice to same position', function () {
    $position = Position::factory()->create();
    $user = \App\Models\User::factory()->create();

    $position->users()->attach($user);

    $payload = [
        'user_id' => $user->id,
    ];

    $response = $this->postJson("/api/v1/positions/{$position->id}/apply", $payload);

    $response
        ->assertStatus(400)
        ->assertJson(['message' => 'User already applied to this position.']);
});
