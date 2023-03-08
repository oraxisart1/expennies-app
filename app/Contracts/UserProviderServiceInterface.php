<?php

namespace App\Contracts;

interface UserProviderServiceInterface
{
    public function getById( int $userId ): ?UserInterface;

    public function getByCredentials( array $credentials ): ?UserInterface;
}