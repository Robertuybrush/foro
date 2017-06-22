<?php

use App\Comment;
use App\Notifications\PostCommented;
use App\Post;
use App\User;
use Illuminate\Notifications\Messages\MailMessage;

class PostCommentedTest extends TestCase
{
    /**
     * @test
     */
    function it_builds_a_mail_message()
    {
        $post = new Post([
            'title' => 'Título del post'
        ]);
        $commenter = new User([
            'name' => 'Robert J.'
        ]);
        $comment = new Comment();
        $comment->post = $post;
        $comment->user = $commenter;

        $notification = new PostCommented($comment);

        $subscriber=new User();

        $message=$notification->toMail($subscriber);

        $this->assertInstanceOf(MailMessage::class, $message);

        $this->assertSame(
            'Nuevo comentario en: Título del post',
            $message->subject
        );

        $this->assertSame(
            'Robert J. escribió un comentario en: Título del post',
            $message->introLines[0]
        );

        $this->assertSame($comment->post->url, $message->actionUrl);
    }
}
