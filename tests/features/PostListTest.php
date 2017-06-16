<?php

use Carbon\Carbon;

class PostListTest extends FeatureTestCase
{
    function test_a_user_can_see_the_posts_list_and_go_to_the_details()
    {
        $post=$this->createPost([
            'title' => '¿Debo ver Gabriel dropout o The perfect insider?'
        ]);

        $this->visit('/')
            ->seeInElement('h1','Posts')
            ->see($post->title)
            ->click($post->title)
            ->seePageIs($post->url);
    }

    function test_posts_list_pagination()
    {
        $post=$this->createPost([
            'title' => 'Post más antiguo',
            'created_at' => Carbon::now()->subDay(2)
        ]);

        factory(\App\Post::class)->times(15)->create([
            'created_at' => Carbon::now()->subDay(1)
        ]);

        $post2=$this->createPost([
            'title' => 'Post más reciente'
        ]);

        $this->visit('/')
            ->dontSee($post->title)
            ->see($post2->title)
            ->click('2')
            ->see($post->title)
            ->dontSee($post2->title);
    }
}
