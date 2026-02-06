<?php

use App\Enums\CompanyPlan;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('creates a company', function () {
    $payload = [
        'name'        => 'Empresa Teste Ltda',
        'description' => 'Descrição da empresa teste',
        'cnpj'        => fake()->unique()->numerify('##############'),
        'plan'        => CompanyPlan::FREE->value,
    ];

    $response = $this->postJson('/api/v1/companies', $payload);

    $response
        ->assertCreated()
        ->assertJsonPath('data.name', $payload['name']);

    $company = Company::where('cnpj', $payload['cnpj'])->first();

    $this->assertDatabaseHas('companies', [
        'name' => $payload['name'],
        'cnpj' => $payload['cnpj'],
        'plan' => $payload['plan'],
    ]);
});

test('lists companies with pagination', function () {
    Company::factory()->count(3)->create();

    $response = $this->getJson('/api/v1/companies');

    $response
        ->assertSuccessful()
        ->assertJsonStructure([
            'data',
            'links' => ['first', 'last', 'prev', 'next'],
            'meta'  => ['current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total'],
        ]);
});

test('shows a company', function () {
    $company = Company::factory()->create();

    $response = $this->getJson("/api/v1/companies/{$company->id}");

    $response
        ->assertSuccessful()
        ->assertJsonPath('data.id', $company->id);
});

test('updates a company', function () {
    $company = Company::factory()->create();

    $payload = [
        'name' => 'Novo Nome Ltda',
        'cnpj' => fake()->unique()->numerify('##############'),
        'plan' => CompanyPlan::PREMIUM->value,
    ];

    $response = $this->putJson("/api/v1/companies/{$company->id}", $payload);

    $response
        ->assertSuccessful()
        ->assertJsonPath('data.name', $payload['name']);

    $this->assertDatabaseHas('companies', [
        'id'   => $company->id,
        'name' => $payload['name'],
        'cnpj' => $payload['cnpj'],
        'plan' => $payload['plan'],
    ]);
});

test('deletes a company', function () {
    $company = Company::factory()->create();

    $response = $this->deleteJson("/api/v1/companies/{$company->id}");

    $response->assertNoContent();

    $this->assertSoftDeleted('companies', ['id' => $company->id]);
});

test('fails to create a company when required fields are missing', function (string $field) {
    $payload = [
        'name'        => 'Empresa Teste Ltda',
        'description' => 'Descrição da empresa teste',
        'cnpj'        => fake()->unique()->numerify('##############'),
        'plan'        => CompanyPlan::FREE->value,
    ];

    unset($payload[$field]);

    $response = $this->postJson('/api/v1/companies', $payload);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors($field);
})->with([
    'name',
    'cnpj',
    'plan',
]);

test('fails to create a company when cnpj is not unique', function () {
    $existing = Company::factory()->create();

    $payload = [
        'name'        => 'Empresa Teste Ltda',
        'description' => 'Descrição da empresa teste',
        'cnpj'        => $existing->cnpj,
        'plan'        => CompanyPlan::FREE->value,
    ];

    $this->postJson('/api/v1/companies', $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors('cnpj');
});

test('fails to create a company when plan is invalid', function () {
    $payload = [
        'name'        => 'Empresa Teste Ltda',
        'description' => 'Descrição da empresa teste',
        'cnpj'        => fake()->unique()->numerify('##############'),
        'plan'        => 'invalid_plan',
    ];

    $this->postJson('/api/v1/companies', $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors('plan');
});

test('fails to update a company when cnpj is not unique', function () {
    $company = Company::factory()->create();
    $other   = Company::factory()->create();

    $payload = [
        'cnpj' => $other->cnpj,
    ];

    $this->putJson("/api/v1/companies/{$company->id}", $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors('cnpj');
});

test('fails to update a company when plan is invalid', function () {
    $company = Company::factory()->create();

    $payload = [
        'plan' => 'invalid_plan',
    ];

    $this->putJson("/api/v1/companies/{$company->id}", $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors('plan');
});

test('returns redirect when accept header is not json', function () {
    $payload = [
        'name'        => 'Empresa Teste Ltda',
        'description' => 'Descrição da empresa teste',
        'cnpj'        => fake()->unique()->numerify('##############'),
        'plan'        => CompanyPlan::FREE->value,
    ];

    $response = $this->post('/api/v1/companies', $payload, ['Content-Type' => 'application/json']);

    $response->assertRedirect('/');
});
