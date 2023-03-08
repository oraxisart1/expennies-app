<?php

namespace App;

use App\Contracts\AuthInterface;
use App\Contracts\UserInterface;
use App\Contracts\UserProviderServiceInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManager;

class Auth implements AuthInterface
{
    private ?UserInterface $user = null;

    public function __construct( private readonly UserProviderServiceInterface $userProvider )
    {
    }

    public function user(): ?UserInterface
    {
        if ( isset( $this->user ) ) {
            return $this->user;
        }

        $userId = $_SESSION[ 'user' ] ?? null;
        if ( !$userId ) {
            return null;
        }

        $user = $this->userProvider->getById( $userId );
        if ( !$user ) {
            return null;
        }

        return $this->user = $user;
    }

    public function attemptLogin( array $credentials ): bool
    {
        /** @var User $user */
        $user = $this->userProvider->getByCredentials( $credentials );
        if ( !$user || !$this->checkCredentials( $user, $credentials ) ) {
            return false;
        }

        session_regenerate_id();

        $_SESSION[ 'user' ] = $user->getId();
        $this->user = $user;

        return true;
    }

    public function checkCredentials( UserInterface $user, array $credentials ): bool
    {
        return password_verify( $credentials[ 'password' ], $user->getPassword() );
    }

    public function logOut(): void
    {
        unset( $_SESSION[ 'user' ] );
        $this->user = null;
    }
}