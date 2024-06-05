<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'name'     => 'required|lowercase|min:3|max:15|unique:users',
			'email'    => 'required|email|unique:users',
			'password' => 'required|lowercase|min:8|max:15|confirmed',
		];
	}
}
