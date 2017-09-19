<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Activity;
use App\Reply;
use Carbon\Carbon;

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


	/** @test*/
	public function it_feches_a_feed_for_any_users()
	{
		$this->signedIn();

		create('App\Thread', ['user_id' => auth()->id()]);
		create('App\Thread', [
			'user_id' => auth()->id(),
			'created_at' => Carbon::now()->subWeek()
		]);

		$feed = Activity::feed(auth()->user());

		$this->assertTrue($feed->keys()->contains(
			Carbon::now()->format('Y-m-d')
		));

	}
}
