<?php

namespace App\Http\Requests;

use App\Rules\English;
use App\Rules\Georgian;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends FormRequest
{
	public function rules(): array
	{
		$english = new English;
		$georgian = new Georgian;
		return [
			'title.en'        => ['sometimes', 'required', $english],
			'title.ka'        => ['sometimes', 'required', $georgian],
			'director.en'     => ['sometimes', 'required', $english],
			'director.ka'     => ['sometimes', 'required', $georgian],
			'description.en'  => ['sometimes', 'required', $english],
			'description.ka'  => ['sometimes', 'required', $georgian],
			'year'            => ['sometimes', 'required', 'numeric', 'between:1900,2100'],
			'poster'          => ['sometimes', 'required', 'image'],
			'genres'          => ['sometimes', 'required', 'array'],
			'genres.*'        => ['integer', 'exists:genres,id'],
		];
	}
}
