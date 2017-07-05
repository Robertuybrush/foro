<?php

use App\Comment;
use Carbon\Carbon;

class ShowCommentsTest extends FeatureTestCase
{
    function test_comments_are_showing()
    {
        $post=$this->createPost();

        $lastComment=factory(Comment::class)->create([
            'comment' => 'Comentario más antiguo',
            'created_at' => Carbon::now()->subDay(2),
            'post_id' => $post->id
        ]);

        factory(Comment::class)->times(15)->create([
            'created_at' => Carbon::now()->subDay(1),
            'post_id' => $post->id
        ]);

        $firstComment=factory(Comment::class)->create([
            'comment' => 'Comentario más reciente',
            'post_id' => $post->id
        ]);

        $this->visit($firstComment->post->url)
            ->see('Comentario más reciente')
            ->dontSee('Comentario más antiguo')
            ->click('2')
            ->dontSee('Comentario más reciente')
            ->see('Comentario más antiguo');
    }

    function test_the_comment_author_shows_in_comments_section()
    {
        $user=$this->defaultUser([
            'first_name' => 'Robert J.'
        ]);

        $comment=factory(Comment::class)->create([
            'user_id' => $user->id
        ]);

        $this->visit($comment->post->url)
            ->seeInElement('.author','Robert J.');
    }
}
