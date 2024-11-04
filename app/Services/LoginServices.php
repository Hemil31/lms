<?php
namespace App\Services;
use Illuminate\Support\Facades\Auth;


/**
 * LoginServices
 *
 * Provides authentication services for user login and logout.
 */
class LoginServices
{

    /**
     * Authenticates the user using the provided credentials and remembers the login if specified.
     *
     * @param array $credentials The user's credentials (e.g., email, password).
     * @param bool $remember Whether to remember the login across sessions.
     * @return array|bool Returns an array containing user information, access token, token type, and expiration time if successful, or false if authentication fails.
     */
    public function login($credentials, $remember)
    {
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            $tokenResult = $user->createToken('appToken');
            $accessToken = $tokenResult->accessToken;
            $tokenExpiresAt = $tokenResult->token->expires_at;
            return [
                'user' => $user,
                'access_token' => $accessToken,
                'token_type' => 'Bearer',
                'expires_at' => $tokenExpiresAt->toDateTimeString(),
            ];
        }
        return false;
    }

    /**
     * Revokes the user's current access token, effectively logging them out.
     *
     * @return bool Returns true if logout is successful, or false if an error occurs.
     */
    public function logout()
    {
        Auth::user()->token()->revoke();
        return true;
    }
    
}
