<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'name'     => 'lowercase|min:3|max:15|required_without_all:password,avatar',
			'password' => 'lowercase|min:8|max:15|confirmed|required_without_all:name,avatar',
			'avatar'   => 'image|required_without_all:password,name',
		];
	}
}
