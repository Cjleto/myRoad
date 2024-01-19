<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\UserService;
use App\Traits\ApiResponses;

/**
 * @group Authentication
 *
 * APIs for user authentication
 */
class LoginController extends Controller
{
    use ApiResponses;

    /**
     * Login
     *
     * Authenticates a user and returns an API token
     *
     * @unauthenticated
     *
     * @bodyParam email string required The email of the user.<br>Use editor/admin@example.com for different permissions<br> Example: admin@example.com
     * @bodyParam password string required The password of the user. Example: password
     *
     * @response {
     *   "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c"
     * }
     * @response 401 {
     *   "message": "Invalid login details"
     * }
     */
    public function __invoke(LoginRequest $request, UserService $userService)
    {
        if (! auth()->attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details',
            ], 401);
        }

        $user = auth()->user();

        $token = $userService->newApiToken($user);

        $responseData = [
            'token' => $token,
        ];

        return $this->success($responseData, 200);
    }
}
