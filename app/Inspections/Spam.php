<?php
namespace App\Inspections;

use App\Inspections\InvalidKeywords;
use App\Inspections\KeyHeldDown;

class Spam
{
    protected $inspections = [
        InvalidKeywords::class,
        KeyHeldDown::class
    ];

    public function detect($body)
    {
        foreach ($this->inspections as $inspection) {
            app($inspection)->detect($body);
        }

        return false;
    }
}