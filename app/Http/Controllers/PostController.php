<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Category;
use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Category $category = null)
    {
        //$posts = Post::all();
        $posts = Post::orderBy('created_at','DESC')
            ->category($category)
            ->paginate();


        $categoryItems = $this->getCategoryItems();

        return view('posts.index',compact('posts', 'category', 'categoryItems'));
    }

    public function show(Post $post,$slug)
    {
        if($post->slug != $slug)
        {
            return redirect($post->url,301);
        }

        $comments = Comment::orderBy('created_at','DESC')->where('post_id',$post->id)->paginate();

        return view('posts.show', compact(['post','comments']));
    }

    protected function getCategoryItems()
    {
        return Category::orderBy('name')->get()->map(function ($category){
            return[
                'title' => $category->name,
                'full_url' => route('posts.index', $category)
            ];
        })->toArray();
    }
}
