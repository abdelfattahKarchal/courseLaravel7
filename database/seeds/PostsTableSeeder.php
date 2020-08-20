<?php

use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users =App\User::all();
    if ($users->count()==0) {
        $this->command->error("Please create some user in your users table");
        return;
    }

        // ask user how many of post want to generat in posts table
        $nbPosts = (int) $this->command->ask("How many of post you want to generat ?",100);

        factory(App\Post::class,$nbPosts)->make()->each(function($post) use($users){
            $post->user_id = $users->random()->id;
            $post->save();
        });
    }
}
