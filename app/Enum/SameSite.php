<?php

namespace App\Enum;

enum SameSite: string
{
    case Lax = 'lax';
    case None = 'none';
    case Strict = 'strict';
}
