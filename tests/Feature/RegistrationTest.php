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
    public function a_confirmation_email_is_sent_upon_registration()
    {
        Mail::fake();

        event(new Registered(create('App\User')));

        Mail::assertSent(PleaseConfirmYourEmail::class);
    }

    /** @test */
    public function user_can_fully_confirm_their_email_address()
    {
        $this->post('/register', [
            'name' => 'vanliem',
            'email' => 'vanliem2204@gmail.com',
            'password' => '123456789',
            'password_confirmation' => '123456789'
        ]);

        $user = User::whereName('vanliem')->first();

        $this->assertFalse($user->confirmed);

        $this->assertNotNull($user->confirmation_token);

        $response = $this->get('/register/confirm?token=' . $user->confirmation_token);

        $this->assertTrue($user->fresh()->confirmed);

        $response->assertRedirect('/threads');
    }
}
