<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationsTest extends TestCase
{
    /**
     * @test
     */
    public function a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_ath_is_not_by_current_user()
    {
        $this->signedIn();

        $thread = create('App\Thread')->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'some reply here'
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'some reply here'
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /**
     * @test
     */
    public function a_user_can_fetch_their_unread_notification()
    {
        $this->signedIn();

        $thread = create('App\Thread')->subscribe();

        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'some reply here'
        ]);

        $username = auth()->user()->name;

        $response = $this->get("/profiles/{$username}/notifications")->json();

        $this->assertCount(1, $response);
    }

    /**
     * @test
     */
    public function a_user_can_mark_a_notification_as_read()
    {
        $this->signedIn();

        $thread = create('App\Thread')->subscribe();

        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'some reply here'
        ]);

        $this->assertCount(1, auth()->user()->unreadNotifications);

        $notificationId = auth()->user()->unreadNotifications->first()->id;

        $username = auth()->user()->name;

        $this->delete("/profiles/{$username}/notifications/{$notificationId}");

        $this->assertCount(0, auth()->user()->fresh()->unreadNotifications);
    }
}
