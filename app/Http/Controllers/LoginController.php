<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\UserService;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class LoginController extends Controller
{

    use ApiResponses;

    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request, UserService $userService)
    {
        if( !auth()->attempt($request->only('email', 'password')) ) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = auth()->user();

        $token = $userService->newApiToken($user);

        $responseData = [
            'user' => $user, // TODO replace with user resource
            'token' => $token
            ];

        return $this->success($responseData, 200);
    }
}
