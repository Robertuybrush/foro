<?php

use App\Notifications\PostCommented;
use App\User;
use Illuminate\Support\Facades\Notification;

class NotifyUsersTest extends FeatureTestCase
{
    function test_the_subscribers_receive_a_notification_when_post_is_commented()
    {
        Notification::fake();

        $post = $this->createPost();
        $subscriber = factory(User::class)->create();

        $subscriber->subscribeTo($post);

        $writer = factory(User::class)->create();

        $writer->subscribeTo($post);

        $comment=$writer->comment($post, 'Un comentario cualquiera');

        Notification::assertSentTo(
            $subscriber, PostCommented::class, function ($notification) use ($comment, $subscriber){
                return $notification->comment->id == $comment->id;
            }
        );

        //the author of the comment shouldn't be notified even if they are a subscriber
        Notification::assertNotSentTo($writer, PostCommented::class);
    }
}
