<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\LoginServices;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\JsonResponse;

/**
 * LoginController handles authentication and authorization operations.
 */
class LoginController extends Controller
{
    use JsonResponseTrait;

    /**
     * Constructor for the LoginController class.
     *
     * @param LoginServices $loginServices The Login service dependency.
     */
    public function __construct(
        protected LoginServices $loginServices
    ) {
        //
    }

    /**
     * Handles user login.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->only('email', 'password');
            $remember = $request->has('remember');
            $data = $this->loginServices->login($credentials, $remember);
            if ($data) {
                return $this->successResponse($data, 'auth.login');
            }
            return $this->errorResponse('auth.invalid_credentials', statusCode: 401);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during login' . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Handles user logout.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            $this->loginServices->logout();
            return $this->successResponse(null, 'auth.logout');
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during logout' . $e->getMessage(), statusCode: 500);
        }
    }
}
