<?php

use App\Models\User;
use App\Notifications\Auth\QueuedResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

beforeEach(function () {
	$this->user = User::factory()->create([
		'name'     => 'johndoe',
		'email'    => 'johndoe@example.com',
		'password' => 'password',
	]);
	$this->withHeaders(['referer' => config('sanctum.stateful')]);
});

describe('login', function () {
	test('users can log in with correct credentials', function () {
		$response = $this->postJson(route('login'), [
			'email'     => 'johndoe@example.com',
			'password'  => 'password',
		]);
		$response->assertStatus(200);
		expect($response['user']['name'])->toBe('johndoe');
	});

	test('users can not log in with incorrect credentials', function () {
		$this
			->postJson(route('login'), [
				'email'     => 'johndoe@example.com',
				'password'  => 'thisiswrong',
			])
			->assertStatus(401);
	});

	test('users can not log in when email not verified', function () {
		$this->user->email_verified_at = null;
		$this->user->save();
		$this
			->postJson(route('login'), [
				'email'     => 'johndoe@example.com',
				'password'  => 'password',
			])
			->assertStatus(403);
	});
});

describe('logout', function () {
	test('users can log out', function () {
		$this
			->actingAs($this->user)
			->postJson(route('logout'))
			->assertStatus(200);
	});

	it('returns unauthorized when not logged in', function () {
		$this
			->postJson(route('logout'))
			->assertStatus(401);
	});
});

describe('password reset', function () {
	test('users can request a password reset link', function () {
		Notification::fake();
		$this
			->postJson(route('password.email', [
				'email' => 'johndoe@example.com',
			]))
			->assertStatus(200);
		$this->assertDatabaseHas('password_reset_tokens', [
			'email' => 'johndoe@example.com',
		]);
		Notification::assertSentTo($this->user, QueuedResetPassword::class);
	});

	test('users can reset password with a valid token', function () {
		$passwordBroker = app('auth.password.broker');
		$token = $passwordBroker->createToken($this->user);
		$this
			->postJson(route('password.update', [
				'email'                 => 'johndoe@example.com',
				'token'                 => $token,
				'password'              => 'newpassword',
				'password_confirmation' => 'newpassword',
			]))
			->assertStatus(200);
		$this->user->refresh();
		expect(Hash::check('newpassword', $this->user->password))->toBeTrue();
	});

	test('users can not reset password without a valid token', function () {
		$this
			->postJson(route('password.update', [
				'email'                 => 'johndoe@example.com',
				'token'                 => 'thisisnotavalidpasswordresettoken',
				'password'              => 'newpassword',
				'password_confirmation' => 'newpassword',
			]))
			->assertStatus(400);
		$this->user->refresh();
		expect(Hash::check('password', $this->user->password))->toBeTrue();
	});
});
