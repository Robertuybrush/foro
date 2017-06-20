<?php

class SupportMarkdownTest extends FeatureTestCase
{
    function test_the_post_content_supports_markdown()
    {
        $importantText = 'Un texto muy importante';

        $post=$this->createPost([
            'content' => "La primera parte del texto. **$importantText**. La última parte del texto"
        ]);

        $this->visit($post->url)
            ->seeInElement('strong', $importantText);
    }

    function test_the_code_in_the_post_is_escaped()
    {
        $xssAttack= "<script>alert('Sharing code')</script>";
        $post=$this->createPost([
            'content' => "`$xssAttack`. Texto normal."
        ]);

        $this->visit($post->url)
            ->dontSee($xssAttack)
            ->seeText($xssAttack);
    }

    function test_xss_attack()
    {
        $xssAttack= "<script>alert('Malicious JS!')</script>";
        $post=$this->createPost([
            'content' => "$xssAttack. Texto normal."
        ]);

        $this->visit($post->url)
            ->dontSee($xssAttack)
            ->seeText($xssAttack);
    }

    function test_xss_attack_with_html()
    {
        $xssAttack= "<img src='img.jpg' />";
        $post=$this->createPost([
            'content' => "$xssAttack. Texto normal."
        ]);

        $this->visit($post->url)
            ->dontSee($xssAttack);
    }

    function test_the_comments_content_support_markdown()
    {
        $importantText = 'Un texto muy importante';

        $comment=factory(App\Comment::class)->create([
            'comment' => "La primera parte del texto. **$importantText**. La última parte del texto"
        ]);

        $this->visit($comment->post->url)
            ->seeInElement('strong', $importantText);
    }

    function test_the_code_in_the_comments_is_escaped()
    {
        $xssAttack= "<script>alert('Sharing code')</script>";
        $comment=factory(App\Comment::class)->create([
            'comment' => "`$xssAttack`. Texto normal."
        ]);

        $this->visit($comment->post->url)
            ->dontSee($xssAttack)
            ->seeText($xssAttack);
    }

    function test_xss_attack_in_comments()
    {
        $xssAttack= "<script>alert('Malicious JS!')</script>";
        $comment=factory(App\Comment::class)->create([
            'comment' => "$xssAttack. Texto normal."
        ]);

        $this->visit($comment->post->url)
            ->dontSee($xssAttack)
            ->seeText($xssAttack);
    }

    function test_xss_attack_with_html_in_comments()
    {
        $xssAttack= "<img src='img.jpg' />";
        $comment=factory(App\Comment::class)->create([
            'comment' => "$xssAttack. Texto normal."
        ]);

        $this->visit($comment->post->url)
            ->dontSee($xssAttack);
    }
}
