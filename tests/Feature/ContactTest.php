<?php

use App\Models\Contact;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('dashboard page with contact form is displayed', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
        );
});

test('contact form can be submitted', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('contact.store'), [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test@example.com',
        'phone' => '123456789',
        'description' => 'This is a test description with more than ten characters.',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect();
    
    $this->assertDatabaseHas('contacts', [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test@example.com',
    ]);
});

test('contact form requires validation', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('contact.store'), [
        'first_name' => '',
        'last_name' => '',
        'email' => 'not-an-email',
        'description' => 'short',
    ]);

    $response->assertSessionHasErrors(['first_name', 'last_name', 'email', 'description']);
    $this->assertDatabaseEmpty('contacts');
});

test('contact form is rate limited', function () {
    $user = User::factory()->create();

    $payload = [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test@example.com',
        'phone' => '123456789',
        'description' => 'This is a test description with more than ten characters.',
    ];

    // First 5 requests should pass
    for ($i = 0; $i < 5; $i++) {
        $this->actingAs($user)
            ->post(route('contact.store'), $payload)
            ->assertRedirect();
    }

    // The 6th request should be throttled
    $this->actingAs($user)
        ->post(route('contact.store'), $payload)
        ->assertStatus(429);
});
