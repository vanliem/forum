<?php

namespace App\Inspections;

use Exception;

class InvalidKeywords
{
    protected $invalidKeywords = [
        'spam text'
    ];

    public function detect($body)
    {
        foreach ($this->invalidKeywords as $keyword) {
            if (stripos($body, $keyword) !== false) {
                throw new Exception('Spam');
            }
        }
    }
}