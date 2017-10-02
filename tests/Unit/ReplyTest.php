<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{
    use RefreshDatabase;
    
    /**  @test */
    public function it_has_an_owner()
    {
    	$reply = factory('App\Reply')->create();

    	$this->assertInstanceOf('App\User', $reply->owner);
    }

    /**  @test */
    public function it_know_if_it_was_just_published()
    {
    	$reply = factory('App\Reply')->create();

    	$this->assertTrue($reply->wasJustPublished());

    	$reply->created_at = Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustPublished());
    }


    /**  @test */
    public function it_can_detect_all_mentioned_users_in_the_body()
    {
        $reply = create('App\Reply', [
            'body' => '@levanliem want to talk to @vanliem'
        ]);

        $this->assertEquals(['levanliem', 'vanliem'], $reply->mentionedUsers());
    }

    /**  @test */
    public function it_wraps_mentioned_in_the_body_within_anchor_tag()
    {
        $reply = create('App\Reply', [
            'body' => 'Hello @vanliem'
        ]);

        $this->assertEquals(
            'Hello <a href="/profiles/vanliem">@vanliem</a>',
            $reply->body
        );
    }


}
