<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Activity;
use App\Reply;

class ActivityTest extends TestCase
{
	/**
	 * @test
	 */
	public function it_records_activity_when_a_thread_is_created()
	{
		$this->signedIn();

		$thread = create('App\Thread');

		$this->assertDatabaseHas('activities', [
			'type' => 'created_thread',
			'user_id' => auth()->id(),
			'subject_id' => $thread->id,
			'subject_type' => get_class($thread),
		]);

		$activity = Activity::first();

		$this->assertEquals($activity->subject->id, $thread->id);
	}

	/** @test*/
	public function it_records_activity_when_a_reply_is_created()
	{
		$this->signedIn();

		$reply = create('App\Reply');

		$this->assertDatabaseHas('activities', [
			'type' => 'created_reply',
			'user_id' => auth()->id(),
			'subject_id' => $reply->id,
			'subject_type' => get_class($reply),
		]);

		$this->assertEquals(2, Activity::count());
	}
}
