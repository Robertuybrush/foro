<?php

use App\Mail\TokenMail;
use App\Token;
use App\User;
use Illuminate\Support\Facades\Mail;

class RegistrationTest extends FeatureTestCase
{
    function test_a_user_can_create_an_account()
    {
        Mail::fake();

        $this->visitRoute('register')
            ->type('robertsimpson2001@hotmail.com','email')
            ->type('bobhm','username')
            ->type('Rober J.','first_name')
            ->type('Hurtado','last_name')
            ->press('Regístrate');

        $this->seeInDatabase('users',[
            'email' => 'robertsimpson2001@hotmail.com',
            'username' => 'bobhm',
            'first_name' => 'Rober J.',
            'last_name' => 'Hurtado'
        ]);

        $user = User::first();

        $this->seeInDatabase('tokens', [
            'user_id' => $user->id
        ]);

        $token = Token::where('user_id', $user->id)->first();

        $this->assertNotNull($token);

        Mail::assertSentTo($user, TokenMail::class, function ($mail) use ($token){
            return $mail->token->id == $token->id;
        });

        $this->seeRouteIs('register_confirmation')
            ->see('Gracias por registrarte')
            ->see('Hemos enviado a tu email un enlace para que inicies sesión');
    }

    function test_register_form_validation()
    {
        $this->visitRoute('register')
            ->press('Regístrate')
            ->see('El campo correo electrónico es obligatorio.');

        $this->visitRoute('register')
            ->type('robertsimpson2001hotmail.com','email')
            ->press('Regístrate')
            ->see('correo electrónico no es un correo válido');

        $this->visitRoute('register')
            ->type('robertsimpson2001@hotmail.com','email')
            ->press('Regístrate')
            ->see('El campo usuario es obligatorio.');

        $this->visitRoute('register')
            ->type('robertsimpson2001@hotmail.com','email')
            ->type('bobhm','username')
            ->press('Regístrate')
            ->see('El campo nombre es obligatorio.');

        $this->visitRoute('register')
            ->type('robertsimpson2001@hotmail.com','email')
            ->type('bobhm','username')
            ->type('Robert J.','first_name')
            ->press('Regístrate')
            ->see('El campo apellido es obligatorio.');
    }
}