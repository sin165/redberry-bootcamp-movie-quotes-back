<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventAuthenticatedAccess
{
	public function handle(Request $request, Closure $next): Response
	{
		if ($request->user()) {
			return response()->json(['message' => 'You must not be logged in to access this route.'], 403);
		}
		return $next($request);
	}
}
