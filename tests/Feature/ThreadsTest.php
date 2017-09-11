<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Thread;

class ThreadsTest extends TestCase
{   
    /** @test */
    public function a_user_can_browse_threads()
    {
        $thread = Thread::first();
        $response = $this->get('/threads');
        $response->assertSee($thread->title);

    }

    /** @test */
    public function a_user_can_read_a_single_thread()
    {
        $thread = Thread::first();
        $response = $this->get('/threads/' . $thread->id);
        
        $response->assertSee($thread->title);
    }
}
