<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowPostTest extends TestCase
{
    function test_a_user_can_see_the_post_details()
    {
        //Having
        $user = $this->defaultUser();
        $title='Este es el título del post';
        $content='Este es el contenido del post';

        $post=factory(\App\Post::class)->make([
            'title' => $title,
            'content' => $content
        ]);

        $user->posts()->save($post);

        //When
        $this->visit($post->url)
            ->seeInElement('h1',$post->title)
            ->see($post->content)
            ->see($user->name);
    }

    function test_old_urls_are_redirected()
    {
        //Having
        $user = $this->defaultUser();

        $post=factory(\App\Post::class)->make([
            'title' => 'Old title'
        ]);

        $user->posts()->save($post);

        $old_url=$post->url;
        $post->update(['title'=>'New title']);

        $this->visit($old_url)
            ->seePageIs($post->url);
    }

    /*function test_post_url_with_wrong_slugs_still_work()
    {
        //Having
        $user = $this->defaultUser();

        $post=factory(\App\Post::class)->make([
            'title' => 'Old title'
        ]);

        $user->posts()->save($post);

        $old_url=$post->url;
        $post->title='New title';

        $post->save();

        $this->visit($old_url)
            ->assertResponseOk()
            ->see('New title');
    }*/
}
