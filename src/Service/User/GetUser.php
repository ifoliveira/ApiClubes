<?php

namespace App\Service\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Model\Exception\User\UserNotFound;

class GetUser
{

    public function __construct(private UserRepository $userRepository)
    {

    }

    public function __invoke(string $username): ?user
    {
        $user = $this->userRepository->findOneBy(array('username' => $username), null);
        if (!$user) {
                userNotFound::throwException();

        }
        return $user;
    }
}