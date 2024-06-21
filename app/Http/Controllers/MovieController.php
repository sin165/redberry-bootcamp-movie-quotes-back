<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MovieController extends Controller
{
	public function index(): AnonymousResourceCollection
	{
		return MovieResource::collection(Movie::all());
	}

	public function store(StoreMovieRequest $request): JsonResponse
	{
		$validatedData = $request->validated();
		$movie = Movie::create($validatedData + ['user_id' => auth()->id()]);
		$movie->genres()->attach($validatedData['genres']);
		$movie->addMediaFromRequest('poster')->toMediaCollection('posters');
		$movie->load('media');
		return response()->json(new MovieResource($movie), 201);
	}

	public function show(Movie $movie): MovieResource
	{
		return new MovieResource($movie);
	}

	public function update(UpdateMovieRequest $request, Movie $movie): MovieResource
	{
		$validatedData = $request->validated();
		$movie->update($validatedData);
		if (isset($validatedData['genres'])) {
			$movie->genres()->sync($validatedData['genres']);
		}
		if ($request->hasFile('poster')) {
			$movie->clearMediaCollection('posters');
			$movie->addMediaFromRequest('poster')->toMediaCollection('posters');
			$movie->load('media');
		}
		return new MovieResource($movie);
	}

	public function destroy(Movie $movie): JsonResponse
	{
		$movie->delete();
		return response()->json(null, 204);
	}
}
