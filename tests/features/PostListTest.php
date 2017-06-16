<?php

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
            'title' => '¿Debo ver Gabriel dropout o The perfect insider?'
        ]);
        $post2=$this->createPost([
            'title' => '¿Debo ver Cowboy Bebop o Evangelion?'
        ]);

        $this->visit('/')
            ->see($post->title)
            ->dontSee($post2->title)
            ->click('2')
            ->dontSee($post->title)
            ->see($post2->title)
            ->click($post2->title)
            ->seePageIs($post2->url);
    }
}
