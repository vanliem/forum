<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Mail\PleaseConfirmYourEmail;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_confirmation_email_is_sent_upon_registration()
    {
        Mail::fake();

        $this->post(route('register'), [
            'name' => 'vanliem',
            'email' => 'vanliem2204@gmail.com',
            'password' => '123456789',
            'password_confirmation' => '123456789'
        ]);

        Mail::assertQueued(PleaseConfirmYourEmail::class);
    }

    /** @test */
    public function user_can_fully_confirm_their_email_address()
    {
        Mail::fake();

        $this->post(route('register'), [
            'name' => 'vanliem',
            'email' => 'vanliem2204@gmail.com',
            'password' => '123456789',
            'password_confirmation' => '123456789'
        ]);

        $user = User::whereName('vanliem')->first();

        $this->assertFalse($user->confirmed);

        $this->assertNotNull($user->confirmation_token);

        $this->get(route('register.confirm', ['token' => $user->confirmation_token]))
            ->assertRedirect(route('threads.index'));

        tap($user->fresh(), function ($user) {
            $this->assertTrue($user->confirmed);
            $this->assertNull($user->confirmation_token);
        });
    }


    /** @test */
    public function confirming_an_invalid_token()
    {
        $this->get(route('register.confirm', ['token' => 'Invalid token']))
            ->assertRedirect(route('threads.index'))
            ->assertSessionHas('flash', 'Unknown token.');

    }
}
