<?php

namespace App\Http\Middleware;

use App\Services\TravelService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TravelIsPublic
{



    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!TravelService::checkPublic($request->travel)) {
            return response()->json(['message' => 'Travel is not public'], 403);
        }

        return $next($request);
    }
}
