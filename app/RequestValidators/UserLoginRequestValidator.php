<?php

namespace App\RequestValidators;

use App\Contracts\RequestValidatorInterface;
use App\Entity\User;
use App\Exception\ValidationException;
use Valitron\Validator;

class UserLoginRequestValidator implements RequestValidatorInterface
{
    public function validate( array $data ): array
    {
        $validator = new Validator( $data );

        $validator->rule( 'required', [ 'email', 'password', ] );
        $validator->rule( 'email', 'email' );
        if ( !$validator->validate() ) {
            throw new ValidationException( $validator->errors() );
        }

        return $data;
    }
}