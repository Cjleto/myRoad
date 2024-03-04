<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\TravelService;
use Symfony\Component\HttpFoundation\Response;
use App\Exceptions\Travel\TravelIsNotPublicException;

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
            throw new TravelIsNotPublicException();
            /* return response()->json(['message' => 'Travel is not public'], 403); */
        }

        return $next($request);
    }
}
