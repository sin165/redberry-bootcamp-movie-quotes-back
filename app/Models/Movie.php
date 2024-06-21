<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Movie extends Model implements HasMedia
{
	use HasFactory, HasTranslations, InteractsWithMedia;

	public $translatable = ['title', 'director', 'description'];

	protected $fillable = ['title', 'director', 'description', 'year', 'user_id'];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function genres(): BelongsToMany
	{
		return $this->belongsToMany(Genre::class);
	}
}
