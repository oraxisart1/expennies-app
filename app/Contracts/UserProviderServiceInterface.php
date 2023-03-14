<?php

namespace App\Contracts;

use App\DataObjects\RegisterUserData;

interface UserProviderServiceInterface
{
    public function getById( int $userId ): ?UserInterface;

    public function getByCredentials( array $credentials ): ?UserInterface;

    public function createUser( RegisterUserData $registerUserData ): UserInterface;
}