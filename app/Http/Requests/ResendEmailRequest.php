<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResendEmailRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'id'    => 'required_without:email|exists:users',
			'email' => 'required_without:id|exists:users',
		];
	}
}
