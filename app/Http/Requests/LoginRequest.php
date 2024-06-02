<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
	protected function prepareForValidation(): void
	{
		if (!$this->has('remember')) {
			$this->merge(['remember' => false]);
		}
	}

	public function rules(): array
	{
		return [
			'email'        => 'required|email',
			'password'     => 'required',
			'remember'     => 'boolean',
		];
	}
}
