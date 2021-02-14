<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class WrongParamException extends Exception
{
    const CODE_5000 = 'Wrong Url';
    const CODE_5001 = 'Title is required';
    const CODE_5002 = 'Title must be less or equal to: ';
}