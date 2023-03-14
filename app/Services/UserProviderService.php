<?php

namespace App\Services;

use App\Contracts\UserInterface;
use App\Contracts\UserProviderServiceInterface;
use App\DataObjects\RegisterUserData;
use App\Entity\User;
use Doctrine\ORM\EntityManager;

class UserProviderService implements UserProviderServiceInterface
{
    public function __construct( private readonly EntityManager $entityManager )
    {
    }

    public function getById( int $userId ): ?UserInterface
    {
        return $this->entityManager->find( User::class, $userId );
    }

    public function getByCredentials( array $credentials ): ?UserInterface
    {
        return $this->entityManager
            ->getRepository( User::class )
            ->findOneBy( [ 'email' => $credentials[ 'email' ] ] );
    }

    public function createUser( RegisterUserData $registerUserData ): UserInterface
    {
        $user = new User();
        $user
            ->setEmail( $registerUserData->email )
            ->setName( $registerUserData->name )
            ->setPassword( password_hash( $registerUserData->password, PASSWORD_BCRYPT, [ 'cost' => 12 ] ) );

        $this->entityManager->persist( $user );
        $this->entityManager->flush();

        return $user;
    }
}