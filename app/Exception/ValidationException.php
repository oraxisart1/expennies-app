<?php

namespace App\Exception;

use Throwable;

class ValidationException extends \Exception
{
    /**
     * @param array $errors
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        public readonly array $errors,
        string $message = 'Validation Error(s)',
        int $code = 422,
        ?Throwable $previous = null
    ) {
        parent::__construct( $message, $code, $previous );
    }
}
