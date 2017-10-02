<?php

namespace App\Exceptions;

use Exception;

class ThrottleException extends Exception
{
    public function render($request)
    {
        return response(
            'You are posting too frequently, Please take a break!',
            429
        );
    }
}