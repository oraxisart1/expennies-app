<?php

namespace App\Contracts;

interface RequestValidatorFactoryInterface
{
    public function make( string $class ): RequestValidatorInterface;
}