<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class English implements ValidationRule
{
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		if (!preg_match('/^[a-zA-Z0-9\p{P}\s]+$/', $value)) {
			$fail('validation.english')->translate();
		}
	}
}
