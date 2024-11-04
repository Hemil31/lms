<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Services\UserServices;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * UserController handles all user-related operations.
 */
class UserController extends Controller
{
    use JsonResponseTrait;

    /**
     * Constructor for the UserController class.
     *
     * @param UserServices $userServices The User services dependency.
     */
    public function __construct(
        protected UserServices $userServices
    ) {
        //
    }

    /**
     * Fetches all user data.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $data = $this->userServices->getAllUser();
        return $this->successResponse($data, 'user.fetch_all', 201);
    }

    /**
     * Creates a new user instance after a valid registration.
     *
     * @param UserCreateRequest $request
     * @return JsonResponse
     */
    public function store(UserCreateRequest $request): JsonResponse
    {
        try {
            $data = $this->userServices->createUser($request->all());
            return $this->successResponse($data, 'user.create', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during register' . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Fetches a specific user by their UUID.
     *
     * @param string $userUuid
     * @return JsonResponse
     */
    public function show(string $userUuid): JsonResponse
    {
        try {
            $data = $this->userServices->getUser($userUuid);
            return $this->successResponse($data, 'user.fetch', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during fetch' . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Updates an existing user.
     *
     * @param UserUpdateRequest $request
     * @param string $userUuid
     * @return JsonResponse
     */
    public function update(UserUpdateRequest $request, string $userUuid): JsonResponse
    {
        $data = $request->all();
        try {
            $data = $this->userServices->updateUser($userUuid, $data);
            return $this->successResponse($data, 'user.update', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during update' . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Deletes a user by their UUID.
     *
     * @param string $userUuid
     * @return JsonResponse
     */
    public function destroy(string $userUuid): JsonResponse
    {
        try {
            $this->userServices->deleteUser($userUuid);
            return $this->successResponse(null, 'user.delete', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during delete' . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Searches for users based on specified criteria.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $data = $this->userServices->searchUser($request->all());
            return $this->successResponse($data, 'user.fetch_all', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during search' . $e->getMessage(), statusCode: 500);
        }
    }
}
