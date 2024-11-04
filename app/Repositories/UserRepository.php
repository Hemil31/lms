<?php

namespace App\Repositories;
use App\Models\User;

class UserRepository extends BaseRepository
{

    /**
     * Summary of __construct
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }

}
