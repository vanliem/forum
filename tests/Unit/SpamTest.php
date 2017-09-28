<?php

namespace Tests\Unit;

use App\Inspections\Spam;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpamTest extends TestCase
{
    /**
     * @test
     */
    public function it_checks_for_invalid_keywords()
    {
        $spam = new Spam();

        $this->assertFalse($spam->detect('correct text'));

        $this->expectException(\Exception::class);

        $spam->detect('spam text');
    }

    /**
     * @test
     */
    public function it_checks_for_any_being_held_down()
    {
        $spam = new Spam();

        $this->expectException(\Exception::class);

        $spam->detect('Hello word aaaaaaa');
    }
}