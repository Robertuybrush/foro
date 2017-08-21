<?php

use App\Token;
use Illuminate\Support\Facades\Mail;

class RequestTokenTest extends FeatureTestCase
{
    function test_a_guest_user_can_request_a_token()
    {
        Mail::fake();
        $user = $this->defaultUser(['email' => 'robertsimpson2001@hotmail.com']);

        $this->visitRoute('token')
            ->type('robertsimpson2001@hotmail.com','email')
            ->press('Solicitar token');

        $token = Token::where('user_id', $user->id)->first();

        $this->assertNotNull($token,'A token was not created');

        /*Mail::assertSent(TokenMail::class,function ($mail) use ($token, $user){
            return $mail->hasTo($user) && $mail->token->id === $token->id;
        });*/

        $this->dontSeeIsAuthenticated();

        $this->see('Hemos enviado a tu email un enlace para que inicies sesión');
    }

    function test_a_guest_user_can_request_a_token_without_an_email()
    {
        $this->visitRoute('token')
            ->press('Solicitar token');

        $this->seeErrors([
            'email' => 'El campo correo electrónico es obligatorio.'
        ]);
    }

    function test_a_guest_user_can_request_a_token_with_an_invalid_email()
    {
        $this->visitRoute('token')
            ->type('Patata','email')
            ->press('Solicitar token');

        $this->seeErrors([
            'email' => 'correo electrónico no es un correo válido'
        ]);
    }

    function test_a_guest_user_can_request_a_token_with_an_unregistered_email()
    {
        $this->defaultUser(['email' => 'robertsimpson2001@hotmail.com']);

        $this->visitRoute('token')
            ->type('patata@frita.com','email')
            ->press('Solicitar token');

        $this->seeErrors([
            'email' => 'Este correo electrónico no existe.'
        ]);
    }
}