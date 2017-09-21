<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavouritesTest extends TestCase
{
    /**
     * @test
     */
    public function guest_cannot_favourite_anything()
    {
        $this->post('replies/1/favourites')
            ->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function an_authenticate_user_can_favourite_any_reply()
    {
        $this->signedIn();
        $reply = create('App\Reply');

        $this->post("replies/{$reply->id}/favourites");

        $this->assertCount(1, $reply->favourites);
    }

    /**
     * @test
     */
    public function an_authenticate_user_may_only_favourite_a_reply_once()
    {
        $this->signedIn();
        $reply = create('App\Reply');

        try {
            $this->post("replies/{$reply->id}/favourites");
            $this->post("replies/{$reply->id}/favourites");
        } catch (\Exception $exception) {
            $this->fail('Fail');
        }

        $this->assertCount(1, $reply->favourites);
    }

    /**
     * @test
     */
    public function an_authenticate_user_can_unfavourite_any_reply()
    {
        $this->signedIn();

        $reply = create('App\Reply');

        $this->post("/replies/{$reply->id}/favourites");
        $this->assertCount(1, $reply->favourites);

        $this->delete("/replies/{$reply->id}/favourites");

        $this->assertCount(0, $reply->refresh()->favourites);
    }    
}
