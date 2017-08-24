<?php

namespace Tests\Browser;

use App\Post;
use App\Category;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreatePostsTest extends DuskTestCase
{

    use DatabaseMigrations;

    protected $title='Esta es una pregunta';
    protected $content='Este es el contenido';

    public function test_a_user_create_a_post()
    {
        $user=$this->defaultUser();

        $category=factory(\App\Category::class)->create();

        $this->browse(function($browser) use ($user,$category)
        {
            $browser->loginAs($user)
                ->visitRoute('posts.create')
                ->type('title',$this->title)
                ->type('content',$this->content)
                ->select('category_id',$category->id)
                ->press('Publicar')
                ->assertPathIs('/posts/1-esta-es-una-pregunta');
        });

        $this->assertDatabaseHas('posts', [
            'title' => $this->title,
            'content' => $this->content,
            'pending' => true,
            'user_id' => $user->id
        ]);

        $post = Post::first();

        $this->assertDatabaseHas('subscriptions', [
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);
    }

    function test_creating_a_post_requires_authentification()
    {
        $this->browse(function($browser)
        {
            $browser->visitRoute('posts.create')
                ->assertRouteIs('token');
        });
    }

    function test_create_post_form_validation()
    {
        $this->browse(function($browser)
        {
            $browser->loginAs($this->defaultUser())
                ->visitRoute('posts.create')
                ->press('Publicar')
                ->assertRouteIs('posts.create')
                ->assertSee('El campo título es obligatorio')
                ->assertSee('El campo contenido es obligatorio');
        });
    }
}
