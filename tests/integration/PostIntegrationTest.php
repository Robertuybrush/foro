<?php

use App\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostIntegrationTest extends TestCase
{
    use DatabaseTransactions;

    function test_a_slug_is_generated_and_saved_to_the_database()
    {
        $post=$this->createPost([
            'title' => 'Cómo instalar Laravel'
        ]);

        /*$this->seeInDatabase('posts',[
            'slug' => 'como-instalar-laravel'
        ]);*/

        $this->assertSame(
            'como-instalar-laravel',
            $post->fresh()->slug
        );

        /*$this->assertSame('como-instalar-laravel',$post->slug);*/
    }

    /*function test_url_getter()
    {
        $user=$this->defaultUser();

        $post=$this->createPost([
            'title' => 'Cómo instalar Laravel'
        ]);

        $user->posts()->save($post);

        $this->visit($post->url);
    }*/
}
