<?php

declare( strict_types = 1 );

namespace App\Controllers;

use App\Entity\User;
use App\Exception\ValidationException;
use Doctrine\ORM\EntityManager;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;
use Valitron\Validator;

class AuthController
{
    public function __construct( private readonly Twig $twig, private readonly EntityManager $entityManager )
    {
    }

    public function loginView( Request $request, Response $response )
    {
        return $this->twig->render( $response, 'auth/login.twig' );
    }

    public function registerView( Request $request, Response $response )
    {
        return $this->twig->render( $response, 'auth/register.twig' );
    }

    public function register( Request $request, Response $response )
    {
        $data = $request->getParsedBody();

        $validator = new Validator( $data );
        $validator->rule( 'required', [ 'name', 'email', 'password', 'confirmPassword' ] );
        $validator->rule( 'email', 'email' );
        $validator->rule( 'equals', 'confirmPassword', 'password' )->label( 'Confirm Password' );
        $validator->rule(
            fn( $field, $value, $params, $fields ) => !$this->entityManager->getRepository( User::class )->count(
                [ 'email' => $value ]
            ),
            'email'
        )->message( 'User with the given email address already exists' );

        if ( !$validator->validate() ) {
            throw new ValidationException( $validator->errors() );
        }

        $user = new User();
        $user
            ->setEmail( $data[ 'email' ] )
            ->setName( $data[ 'name' ] )
            ->setPassword( password_hash( $data[ 'password' ], PASSWORD_BCRYPT, [ 'cost' => 12 ] ) );

        $this->entityManager->persist( $user );
        $this->entityManager->flush();

        return $response;
    }

    public function logIn( Request $request, Response $response )
    {
        $data = $request->getParsedBody();

        $validator = new Validator( $data );

        $validator->rule( 'required', [ 'name', 'password', ] );
        $validator->rule( 'email', 'email' );
        if ( !$validator->validate() ) {
        }

        /** @var User $user */
        $user = $this->entityManager->getRepository( User::class )->findOneBy( [ 'email' => $data[ 'email' ] ] );
        if ( !$user || !password_verify( $data[ 'password' ], $user->getPassword() ) ) {
            throw new ValidationException( [ 'password' => [ 'You have entered an invalid username or password' ] ] );
        }

        session_regenerate_id();

        $_SESSION[ 'user' ] = $user->getId();

        return $response->withHeader( 'Location', '/' )->withStatus( 302 );
    }

    public function logOut( Request $request, Response $response )
    {
        return $response->withHeader( 'Location', '/' )->withStatus( 302 );
    }
}
