<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Post;
use Illuminate\Http\Request;

class PostCommentController extends Controller
{
    /**
     * fonction permet d'enregistrer un commentaire tout en associant à un post passé en paramètre(id de post)
     * on passe l id de post depuis la vue et dans le paramètre de la fonction store on inject le modèle Post
     * alors que laravel comprend que (injection de (Post $post) = $post=Post::findOrFail($id))
     *
     * @param Request $request
     * @param Post $post
     * @return void
     */
    public function store(StoreCommentRequest $request,Post $post){
        $post->comments()->create([
            'content'=> $request->content,
            'user_id' => $request->user()->id,
        ]);
        return redirect()->back();
    }
}
