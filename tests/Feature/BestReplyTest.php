<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BestReplyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_creator_thread_may_mark_any_reply_as_the_best_reply()
    {
        $this->signedIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $replies = create('App\Reply', ['thread_id' => $thread->id], 2);

        $this->postJson(route('best-replies.store', [$replies[1]->id]));

        $this->assertTrue($replies[1]->fresh()->isBest());
    }

    /**
     * @test
     */
    public function only_the_creator_thread_may_mark_a_best_reply()
    {
        $this->signedIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $replies = create('App\Reply', ['thread_id' => $thread->id], 2);

        $this->signedIn(create('App\User'));

        $this->postJson(route('best-replies.store', [$replies[1]->id]))
            ->assertStatus(403);

        $this->assertFalse($replies[1]->fresh()->isBest());
    }
}
