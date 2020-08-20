<?php

namespace Tests\Feature;

use App\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function testSavePost()
    {
        $post = new Post();
        $post->title ='new title to test';
        $post->slug = Str::slug($post->title,'-');
        $post->content ='new content';
        $post->active = false;

        $post->save();

        $this->assertDatabaseHas('posts',[
            'title'=>'new title to test'
        ]);
    }

    public function testPostStoreValid()
    {
        $data = [
            'title'=> 'test our post store',
            'slug' => 'test our slug',
            'content' => 'test our content',
            'active' => false,
        ];

        $this->post('/posts',$data)
        ->assertStatus(302)
        ->assertSessionHas('status');

        $this->assertEquals(session('status'),'post was created !');
    }

    public function testPostStoreFail()
    {
        $data = [
            'title'=> '',
            'content' => '',
        ];

        $this->post('/posts',$data)
        ->assertStatus(302)
        ->assertSessionHas('errors');

        $messages = session('errors')->getMessages();

        //dd($messages);
        $this->assertEquals($messages['title'][0],'The title must be at least 4 characters.');
        $this->assertEquals($messages['title'][1],'The title field is required.');
        $this->assertEquals($messages['content'][0],'The content field is required.');

    }

    public function testPostUpdate()
    {
        $post = new Post();
        $post->title ='second title to test';
        $post->slug = Str::slug($post->title,'-');
        $post->content ='new content';
        $post->active = true;

        $post->save();

        $this->assertDatabaseHas('posts',$post->toArray());

        $data = [
            'title'=> 'test our post updated',
            'slug' => Str::slug('test our post updated','-'),
            'content' => 'test our content updated',
            'active' => false,
        ];
        $this->put("/posts/{$post->id}", $data)
        ->assertStatus(302)
        ->assertSessionHas('status');

        $this->assertDatabaseHas('posts',[
            'title' => $data['title']
        ]);

        $this->assertDatabaseMissing('posts',[
            'title'=> $post->title
        ]);

    }


    public function testPostDelete(){
        $post = new Post();
        $post->title ='second title to test';
        $post->slug = Str::slug($post->title,'-');
        $post->content ='new content';
        $post->active = true;

        $post->save();
        $this->assertDatabaseHas('posts',$post->toArray());

        $this->delete("/posts/{$post->id}")
        ->assertStatus(302)
        ->assertSessionHas('status');

        $this->assertDatabaseMissing('posts', $post->toArray());

    }


}
