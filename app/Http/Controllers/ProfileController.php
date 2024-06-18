<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;

class ProfileController extends Controller
{
	public function update(UpdateProfileRequest $request): UserResource
	{
		$user = $request->user();
		$attributes = $request->validated();
		if (isset($attributes['name'])) {
			$user->name = $attributes['name'];
		}
		if (isset($attributes['password'])) {
			$user->password = $attributes['password'];
		}
		if (isset($attributes['name']) || isset($attributes['password'])) {
			$user->save();
		}
		if (isset($attributes['avatar'])) {
			$currentAvatar = $user->getFirstMedia('avatars');
			if ($currentAvatar) {
				$currentAvatar->delete();
			}
			$user->addMediaFromRequest('avatar')->toMediaCollection('avatars');
			$user->load('media');
		}
		return new UserResource($user);
	}
}
