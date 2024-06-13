<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Mockery\MockInterface;

uses(RefreshDatabase::class);

beforeEach(function () {
	$this->withHeaders(['referer' => config('sanctum.stateful')]);
});

it('can redirect to Google', function () {
	$response = $this->getJson(route('google.redirect'));
	$response->assertStatus(200);
	expect($response['url'])->toContain('accounts.google.com');
});

it('can handle Google callback and register and login a new user', function () {
	$user = $this->mock('Laravel\Socialite\Contracts\User', function (MockInterface $mock) {
		$mock->id = '12345';
		$mock->name = 'Jane Doe';
		$mock->email = 'janedoe@example.com';
		$mock->avatar = 'https://laravel.com/img/logomark.min.svg';
	});
	$provider = $this->mock('Laravel\Socialite\Contracts\Provider', function (MockInterface $mock) use ($user) {
		$mock->shouldReceive('user')->andReturn($user);
	});
	Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

	$response = $this->withSession([])->getJson(route('google.callback'));

	$this->assertDatabaseHas('users', [
		'email'     => 'janedoe@example.com',
		'google_id' => '12345',
	]);

	$response->assertStatus(200);
	expect($response['user']['name'])->toBe('Jane Doe');
});

it('can handle Google callback and login an existing user', function () {
	User::factory()->create([
		'name'  => 'Jane Doe',
		'email' => 'janedoe@example.com',
	]);

	$user = $this->mock('Laravel\Socialite\Contracts\User', function (MockInterface $mock) {
		$mock->id = '12345';
		$mock->name = 'Jane Doe';
		$mock->email = 'janedoe@example.com';
		$mock->avatar = 'https://laravel.com/img/logomark.min.svg';
	});
	$provider = $this->mock('Laravel\Socialite\Contracts\Provider', function (MockInterface $mock) use ($user) {
		$mock->shouldReceive('user')->andReturn($user);
	});
	Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

	$response = $this->withSession([])->getJson(route('google.callback'));
	$response->assertStatus(200);
	expect($response['user']['name'])->toBe('Jane Doe');
});
