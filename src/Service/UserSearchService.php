<?php

namespace App\Service;

use App\Repository\UserRepository;

class UserSearchService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function userSearch(mixed $search): mixed
    {
        $users = [];
        if (!empty($search)) {
            $users = $this->userRepository->findLikeAll($search);
        }
        if (empty($search)) {
            // if search is empty, display everything
            $users = $this->userRepository->findByRoles($search);
        }
        return $users;
    }
}
