<?php
namespace App\Services;
use App\Models\User;
use App\Repositories\UserRepository;

/**
 * UserServices class provides an interface for interacting with user data.
 */
class UserServices
{
    /**
     * Constructor for the UserServices class.
     *
     * @param UserRepository $userRepository The UserRepository dependency.
     */
    public function __construct(
        protected UserRepository $userRepository
    ) {
        //
    }

    /**
     * Creates a new user.
     *
     * @param array $data
     * @return mixed
     */
    public function createUser($data)
    {
        return $this->userRepository->create($data);
    }

    /**
     * Updates an existing user by their UUID.
     *
     * @param string $userUuid
     * @param array $data
     * @return mixed
     */
    public function updateUser(string $userUuid, $data)
    {
        return $this->userRepository->updateByUuid($userUuid, $data);
    }

    /**
     * Deletes a user by their UUID.
     *
     * @param string $userUuid
     * @return mixed
     */
    public function deleteUser(string $userUuid)
    {
        return $this->userRepository->deleteByUuid($userUuid);
    }

    /**
     * Retrieves all users.
     *
     * @return mixed
     */
    public function getAllUser()
    {
        return $this->userRepository->paginate();
    }

    /**
     * Retrieves a user by their UUID.
     *
     * @param string $userUuid
     * @return mixed
     */
    public function getUser(string $userUuid)
    {
        return $this->userRepository->findByUuid($userUuid);
    }

    /**
     * Searches for users based on specified criteria.
     *
     * @param array $data
     * @return mixed
     */
    public function searchUser(array $data)
    {
        $search = [];
        if (isset($data['search_terms'])) {
            $search = User::search($data['search_terms'])->get();
        }

        if (isset($data['name'])) {
            $search = $this->userRepository->filter('name', $data['name']);
        }

        if (isset($data['role_id'])) {
            $search = $this->userRepository->filter('role_id', $data['role_id']);
        }
        if (isset($data['email'])) {
            $search = $this->userRepository->filter('email', $data['email']);
        }
        return $search;
    }

}
