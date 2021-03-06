<?php

use App\Category;
use Illuminate\Database\Seeder;

class PostTableSeeder extends Seeder
{
    public function run()
    {
    	$categories = Category::select('id')->get();

    	foreach(range(1,100) as $i){
    		factory(\App\Post::class)->create([
    			'category_id' => $categories->random()->id
    		]);
    	}
    }
}
