<?php

use App\Comment;
use App\Notifications\PostCommented;
use App\Post;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Notifications\Messages\MailMessage;

class PostCommentedTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    function it_builds_a_mail_message()
    {
        $post = factory(Post::class)->create([
            'title' => 'Título del post'
        ]);
        $commenter = factory(User::class)->create([
            'name' => 'Robert J.'
        ]);
        $comment = $commenter->comment($post, 'Estoy haciendo un comentario');

        $notification = new PostCommented($comment);

        $subscriber=factory(User::class)->create();

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
