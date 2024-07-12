<?php

namespace Articles\Exceptions;

use Exception;

class InvalidInputException extends Exception
{
    public function getMessages(): string
    {
         return 'Invalid input!';
    }
}