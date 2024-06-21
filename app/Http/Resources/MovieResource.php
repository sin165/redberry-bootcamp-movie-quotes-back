<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
	public static $wrap = null;

	public function toArray(Request $request): array
	{
		return [
			'id'          => $this->id,
			'title'       => $this->title,
			'director'    => $this->director,
			'description' => $this->description,
			'year'        => $this->year,
			'genres'      => GenreResource::collection($this->genres),
			'poster'      => $this->getFirstMedia('posters')?->getFullUrl(),
		];
	}
}
