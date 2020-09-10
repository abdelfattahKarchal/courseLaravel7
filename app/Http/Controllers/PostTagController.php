<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;
/**
 * permet le regroupement de posts selon leur tag
 */
class PostTagController extends Controller
{

    public function index($id)
    {
        /**
        * injection automatique des variables () dans les vues specifiées sur le fichier Providers/AppServiceProvider.php
        * à laide de la notion de view Composer
        */
        $tag = Tag::find($id);
        return view('posts.index',[
            'posts' => $tag->posts()->withCount('comments')->with(['user','tags'])->get(),
           /*  'mostCommented'=> [],
            'mostUsersActive'=> [],
            'mostUsersActiveInLastMonth'=> [], */
        ]);
    }
}
