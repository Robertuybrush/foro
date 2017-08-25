<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    public function run()
    {
        factory(\App\User::class)->create([
        	'role' => 'admin',
        	'first_name' => 'KuriGohan',
        	'last_name' => 'and Kamehameha',
        	'username' => 'kurigohan',
        	'email' => 'robertsimpson2001@hotmail.com'
        ]);
    }
}
