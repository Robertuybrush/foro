<?php

use App\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostIntegrationTest extends TestCase
{
    use DatabaseTransactions;

    function test_a_slug_is_generated_and_saved_to_the_database()
    {
        $user=$this->defaultUser();

        $post=factory(Post::class)->make([
            'title' => 'Cómo instalar Laravel'
        ]);

        $user->posts()->save($post);

        /*$this->seeInDatabase('posts',[
            'slug' => 'como-instalar-laravel'
        ]);

        $this->assertSame(
            'como-instalar-laravel',
            $post->fresh()->slug
        );*/

        $this->assertSame('como-instalar-laravel',$post->slug);
    }
}