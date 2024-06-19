<?php

namespace App\Http\Requests;

use App\Rules\English;
use App\Rules\Georgian;
use Illuminate\Foundation\Http\FormRequest;

class StoreMovieRequest extends FormRequest
{
	public function rules(): array
	{
		$english = new English;
		$georgian = new Georgian;
		return [
			'title.en'        => ['required', $english],
			'title.ka'        => ['required', $georgian],
			'director.en'     => ['required', $english],
			'director.ka'     => ['required', $georgian],
			'description.en'  => ['required', $english],
			'description.ka'  => ['required', $georgian],
			'year'            => ['required', 'numeric', 'between:1900,2100'],
			'poster'          => ['required', 'image'],
			'genres'          => ['required', 'array'],
			'genres.*'        => ['integer', 'exists:genres,id'],
		];
	}
}
