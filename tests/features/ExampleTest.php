<?php

class ExampleTest extends FeatureTestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    function test_basic_example()
    {
        $name='Rober J.';
        $email='robertsimpson2001@hotmail.com';
        $user = factory(\App\User::class)->create([
            'name' => $name,
            'email' => $email
        ]);

        $this->actingAs($user,'api')
            ->visit('api/user')
            ->see($name)
            ->see($email);
    }
}