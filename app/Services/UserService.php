<?php

namespace App\Services;

use App\Models\User;
use Laravel\Sanctum\NewAccessToken;

class UserService {

    /**
     * @param User $user
     * @return string
     *
     * @throws \Exception
     *
     *
     */
    public function newApiToken (User $user): string
    {

        $expiredAt = now()->addMinutes(config('sanctum.expiration'));

        $permissions = $user->role->permissions->pluck('name')->toArray();

        $newAccessToken = $user->createToken('authToken',$permissions, $expiredAt);

        $token = $newAccessToken->plainTextToken;

        // disable old tokens if user is already logged in
        if(auth('sanctum')->check()){
            self::setExpiredOldTokens($user, $newAccessToken);
        }

        return $token;
    }

    private function deleteOldTokens (User $user, NewAccessToken $newAccessToken): void
    {

        $user->tokens()
            ->where('id', '!=', $newAccessToken->accessToken->id)
            ->delete();
    }

    private function setExpiredOldTokens (User $user, NewAccessToken $newAccessToken): void
    {

        $tokens = $user->tokens()
            ->where('id', '!=', $newAccessToken->accessToken->id)
            ->get();

        foreach ($tokens as $token) {
            $token->forceFill([
                'expires_at' => now(),
            ])->save();
        }
    }

}
