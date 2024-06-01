<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
	public function register(RegisterRequest $request): JsonResponse
	{
		$user = User::create($request->validated());
		event(new Registered($user));
		return response()->json(['message' => 'User registered successfully'], 201);
	}

	public function verifyEmail(Request $request): JsonResponse
	{
		$user = User::findOrFail($request->id);
		if ($user->hasVerifiedEmail()) {
			return response()->json(['message' => 'Email already verified']);
		}
		$user->markEmailAsVerified();
		event(new Verified($user));
		return response()->json(['message' => 'Email verified successfully']);
	}
}
