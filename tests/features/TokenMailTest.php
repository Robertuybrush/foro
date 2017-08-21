<?php

use App\User;
use App\Token;
use \Symfony\Component\DomCrawler\Crawler;
use \Illuminate\Mail\Mailable;

class TokenMailTest extends FeatureTestCase
{
    function test_it_sends_a_link_with_the_token()
    {
    	$token = new User([
    		'first_name' => 'Rober',
    		'last_name' => 'Hurtado',
    		'email' => 'rohurmar@gmail.com'
    	]);

    	$token = new Token([
    		'token' => 'this-is-a-token'
    	]);

    	$this->open(new App\Mail\TokenMail($token))
    		->seeLink($token->url, $token->url);
    }


    protected function open(Mailable $mailable)
    {

		$transport = Mail::getSwiftMailer()->getTransport();

		$transport->flush();

    	Mail::send($mailable);

        $message = $transport->messages()->first();

        $this->crawler = new Crawler($message->getBody());

        return $this;
    }
}