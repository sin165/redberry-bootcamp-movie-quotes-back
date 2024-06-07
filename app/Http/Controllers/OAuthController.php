<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
	public function redirectToGoogle(): JsonResponse
	{
		$url = Socialite::driver('google')->redirect()->getTargetUrl();

		return response()->json(['url' => $url]);
	}

	public function handleGoogleCallback(Request $request): JsonResponse
	{
		$googleUser = Socialite::driver('google')->user();
		$user = User::where('email', $googleUser->email)->first();
		if ($user) {
			if (!$user->google_id) {
				$user->google_id = $googleUser->id;
				$user->save();
			}
			if (!$user->email_verified_at) {
				$user->markEmailAsVerified();
				event(new Verified($user));
			}
		} else {
			$user = User::create([
				'google_id' => $googleUser->id,
				'name'      => $googleUser->name,
				'email'     => $googleUser->email,
				'password'  => rand(100000, 999999),
			]);
			$user->markEmailAsVerified();
			event(new Verified($user));
		}
		Auth::login($user);
		$request->session()->regenerate();
		return response()->json([
			'message' => 'Login successful',
			'user'    => new UserResource($user),
		]);
	}
}
