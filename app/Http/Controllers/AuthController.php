<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResendEmailRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SendResetLinkRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

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

	public function login(LoginRequest $request): JsonResponse
	{
		$data = $request->validated();

		$user = User::where('email', $data['email'])->first();
		if ($user && !$user->hasVerifiedEmail()) {
			return response()->json(['message' => 'Email not verified', 'email' => $data['email']], 403);
		}

		if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']], $data['remember'])) {
			$request->session()->regenerate();
			$user = Auth::user();
			return response()->json([
				'message' => 'Login successful',
				'user'    => new UserResource($user),
			]);
		}

		return response()->json(['message' => 'Login failed'], 401);
	}

	public function getCurrentUser(Request $request): UserResource
	{
		return new UserResource($request->user());
	}

	public function logout(Request $request): JsonResponse
	{
		Auth::guard('web')->logout();
		$request->session()->invalidate();
		$request->session()->regenerateToken();
		return response()->json(['message' => 'Logged out']);
	}

	public function resendEmail(ResendEmailRequest $request): JsonResponse
	{
		$user = User::where('email', $request->validated()['email'])->first();
		$user->sendEmailVerificationNotification();
		return response()->json(['message' => 'Verification link sent!']);
	}

	public function sendResetLink(SendResetLinkRequest $request): JsonResponse
	{
		$status = Password::sendResetLink(
			$request->validated()
		);

		if ($status === Password::RESET_LINK_SENT) {
			return response()->json(['message' => __($status)]);
		} else {
			return response()->json(['message' => __($status)], 400);
		}
	}

	public function resetPassword(ResetPasswordRequest $request): JsonResponse
	{
		$status = Password::reset(
			$request->validated(),
			function (User $user, string $password) {
				$user->forceFill([
					'password' => $password,
				])->setRememberToken(Str::random(60));
				$user->save();
				event(new PasswordReset($user));
			}
		);
		if ($status === Password::PASSWORD_RESET) {
			return response()->json(['message' => __($status)]);
		} else {
			return response()->json(['message' => __($status)], 400);
		}
	}
}
