<?php

use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = App\Post::all();

        if ($posts->count()==0) {
            $this->command->info("Please create some post in your posts table");
            return;
        }
        // ask user how many of post want to generat in comments table
        $nbComments = (int) $this->command->ask("How many of comment you want to generat ?",1000);

        factory(App\Comment::class,2000)->make()->each(function($comment) use($posts){
            $comment->post_id = $posts->random()->id;
            $comment->save();
        });
    }
}
