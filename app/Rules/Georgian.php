<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Georgian implements ValidationRule
{
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		if (!preg_match('/^[ა-ჰ0-9\p{P}\s]+$/', $value)) {
			$fail('validation.georgian')->translate();
		}
	}
}
