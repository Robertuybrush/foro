<?php

namespace App;

use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = ['title','content','category_id'];

    protected $casts=[
        'pending' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function subscribers()
    {
        return $this->belongsToMany(User::class, 'subscriptions');
    }

    /*public function latestComments()
    {
        $comments=$this->comments()->orderBy('created_at','DESC')->paginate();
        return view('posts.commentIndex',compact('comments'));
    }*/

    public function scopeCategory($query, Category $category)
    {
        if($category->exists){
            $query->where('category_id', $category->id);
        }
    }

    public function scopePending($query)
    {
        $query->where('pending',true);
    }

    public function scopeCompleted($query)
    {
        $query->where('pending',false);
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title']=$value;
        $this->attributes['slug']=Str::slug($value);
    }

    public function getUrlAttribute()
    {
        return route('posts.show',[$this->id,$this->slug]);
    }

    public function getSafeHtmlContentAttribute()
    {
        return Markdown::convertToHtml(e($this->content));
    }
}
