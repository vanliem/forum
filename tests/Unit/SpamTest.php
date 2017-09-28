<?php

namespace Tests\Unit;

use App\Spam;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpamTest extends TestCase
{
    /**
     * @test
     */
    public function it_validates_spam()
    {
        $spam = new Spam();

        $this->assertFalse($spam->detect('correct text'));
    }
}