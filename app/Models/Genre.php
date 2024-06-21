<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class Genre extends Model
{
	use HasFactory, HasTranslations;

	public $translatable = ['name'];

	protected $fillable = ['name'];

	public function movies(): BelongsToMany
	{
		return $this->belongsToMany(Movie::class);
	}
}
