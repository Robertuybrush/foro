<?php

class ShowPostTest extends FeatureTestCase
{
    function test_a_user_can_see_the_post_details()
    {
        //Having
        $user = $this->defaultUser([
            'first_name' => 'Rober!'
        ]);
        $title='Este es el título del post';
        $content='Este es el contenido del post';

        $post=$this->createPost([
            'title' => $title,
            'content' => $content,
            'user_id'=>$user->id
        ]);

        //When
        $this->visit($post->url)
            ->seeInElement('h1',$post->title)
            ->see($post->content)
            ->see($user->name)
            ->see('Rober!');
    }

    function test_old_urls_are_redirected()
    {
        //Having
        $post=$this->createPost([
            'title' => 'Old title'
        ]);

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
