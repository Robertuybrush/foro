<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        //$posts = Post::all();
        $posts = Post::orderBy('created_at','DESC')->paginate();

        return view('posts.index',compact('posts'));
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
}
