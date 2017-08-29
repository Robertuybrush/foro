<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Category;
use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Category $category = nul, Request $request)
    {
        //$posts = Post::all();

        $posts = Post::orderBy('created_at','DESC')
            ->scopes($this->getListScopes($category, $request))
            ->latest()
            ->paginate();


        $categoryItems = $this->getCategoryItems($request);

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

    protected function getCategoryItems(Request $request)
    {
        $routeName = $request->route()->getName();

        return Category::query()
            ->orderBy('name')
            ->get()
            ->map(function ($category) use ($routeName){
                return[
                    'title' => $category->name,
                    'full_url' => route($routeName, $category)
                ];
            })
            ->toArray();
    }

    protected function getListScopes(Category $category, Request $request)
    {
        $scopes=[];

        if($category->exists)
        {
            $scopes['category'] = [$category];
        }

        $routeName = $request->route()->getName();

        if($routeName == 'posts.pending')
        {
            $scopes[]='pending';
        }
        
        if($routeName == 'posts.completed')
        {
            $scopes[]='completed';
        }

        return $scopes;
    }
}
