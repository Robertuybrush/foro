<?php

use App\Token;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthenticationTest extends FeatureTestCase
{
    public function test_a_user_can_login_with_a_token_url()
    {
        $user = $this->defaultUser();

        $token = Token::generateFor($user);

        $this->visit("login/{$token->token}");

        $this->seeIsAuthenticated()
            ->seeIsAuthenticatedAs($user);

        $this->dontSeeInDatabase('tokens',[
            'id' => $token->id
        ]);

        $this->seePageIs(url('/'));
    }

    public function test_a_user_cannot_login_with_an_invalid_token()
    {
        $user = $this->defaultUser();

        $token = Token::generateFor($user);

        $invalidToken = str_random(60);

        $this->visit("login/{$invalidToken}");

        $this->dontSeeIsAuthenticated()
            ->seeRouteIs('token')
            ->see('Este enlace ya expiró, por favor solicita otro');

        $this->seeInDatabase('tokens',[
            'id' => $token->id
        ]);
    }

    public function test_a_user_cannot_use_the_same_token_twice()
    {
        $user = $this->defaultUser();

        $token = Token::generateFor($user);

        $token->login();

        Auth::logout();

        $this->visit("login/{$token->token}");

        $this->dontSeeIsAuthenticated()
            ->seeRouteIs('token')
            ->see('Este enlace ya expiró, por favor solicita otro');
    }

    public function test_the_token_expires_after_30_minutes()
    {
        $user = $this->defaultUser();

        $token = Token::generateFor($user);

        Carbon::setTestNow(Carbon::parse('+31 minutes'));

        $this->visit("login/{$token->token}");

        $this->dontSeeIsAuthenticated()
            ->seeRouteIs('token')
            ->see('Este enlace ya expiró, por favor solicita otro');
    }

    public function test_the_token_is_case_sensitive()
    {
        $user = $this->defaultUser();

        $token = Token::generateFor($user);

        $this->visitRoute('login',['token' => strtolower($token->token)]);

        $this->dontSeeIsAuthenticated()
            ->seeRouteIs('token')
            ->see('Este enlace ya expiró, por favor solicita otro');
    }
}