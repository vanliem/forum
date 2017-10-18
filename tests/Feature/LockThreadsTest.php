<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LockThreadsTest extends TestCase
{
	use RefreshDatabase;

    /** @test */
    public function an_admin_can_lock_any_thread()
    {
    	$this->signedIn();

        $thread = create('App\Thread');

        $thread->lock();

        $this->post($thread->path() . '/replies', [
        	'user_id' => create('App\User')->id, 
        	'body' => 'Foobar'
        ])->assertStatus(422);
    }
}
