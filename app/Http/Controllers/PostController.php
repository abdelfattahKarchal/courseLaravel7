<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Image;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\CommonMark\Inline\Element\Strong;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //dd(now());
        /**
         * berchmarcking
         */
        // DB::connection()->enableQueryLog();

        // $posts = Post::with('comments')->get();
        // foreach($posts as $post){
        //     foreach($post->comments as $commment){
        //         dump($commment);
        //     }
        // }
        // dd(DB::getQueryLog());

        // $posts = Post::withCount('comments')->orderBy('updated_at','desc')->get();
        // scoped by orderBy
        // $posts = Post::withCount('comments')->with(['user','tags'])->get();
        // remplacé par le code soped de la méthode PostWithCommentsTags dans le model Post
        $posts = Post::PostWithCommentsTags()->get();

        /**
         * gestion de cahe pour les posts les plus commentés
         * la 1er execution de la page on va récupérer les données depuis la base de données
         * et si en refreche la pas dans moins de 10 seconde on va récupérer les données juste de puis le cache
         */


        /**
         * ces variables j ai les externalisé dons une class ActivityComposer afin que je puisse les injectés
         * automatiquement lors de lancement des vue spécifique sur le fichier Providers/AppServiceProvider.php
         */

        /* $mostCommented = Cache::remember('mostCommented', now()->addSeconds(10), function () {
            return Post::mostCommented()->take(5)->get();
        });
        $mostUsersActive = Cache::remember('mostUsersActive', now()->addSeconds(10), function () {
            return User::mostUsersActive()->take(5)->get();
        });
        $mostUsersActiveInLastMonth = Cache::remember('mostUsersActiveInLastMonth', now()->addSeconds(10), function () {
            return User::mostUsersActiveInLastMonth()->take(5)->get();
        }); */
        return view('posts.index', [
            'posts' => $posts,
            /*  'mostCommented'=> $mostCommented,
            'mostUsersActive'=> $mostUsersActive,
            'mostUsersActiveInLastMonth'=> $mostUsersActiveInLastMonth, */
            'tab' => 'list'
        ]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function archive()
    {
        // $posts = Post::onlyTrashed()->withCount('comments')->orderBy('updated_at','desc')->get();
        // scoped by orderBy
        $posts = Post::onlyTrashed()->withCount('comments')->get();
        return view('posts.index', [
            'posts' => $posts,
            'tab' => 'archive'
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        // $posts = Post::withTrashed()->withCount('comments')->orderBy('updated_at','desc')->get();
        // scoped by orderBy
        $posts = Post::withTrashed()->withCount('comments')->get();
        return view('posts.index', [
            'posts' => $posts,
            'tab' => 'all'
        ]);
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /**
         * definition du key dynamique pour qu on puisse le récupérer depuis le model lors de updating
         */
        $postShow = Cache::remember("post-show-{$id}", 60, function () use ($id) {
            return Post::with(['comments', 'tags', 'comments.user'])->findOrFail($id);
        });

        return view('posts.show', [
            'post' => $postShow
        ]);
    }

    public function create()
    {
        //$this->authorize("post.create");
        $this->authorize("create", Post::class);
        return view('posts.create');
    }

    public function store(StorePostRequest $request)
    {
        /**
         * upload file
         */
       /*  $hasFile = $request->hasFile('picture');
        dump($hasFile); */
        //if ($hasFile) {
            /* $file = $request->file('picture');
            dump($file);
            dump($file->getClientMimeType());
            dump($file->getClientOriginalExtension());
            dump($file->getClientOriginalName()); */

             /*  store file (default value difine in .env FILESYSTEM_DRIVER) */
            // dump($file->store('thumbnails'));
            //Store file withe Storage Facade
            // dump(Storage::putFile('thumbnails',$file));

            //Storage with specifing disk (local=> storage/app/)
            //dump(Storage::disk('local')->putFile('thumbnails',$file));

            //Rename file with storeAs()
            // dump($file->storeAs('thumbnails', random_int(1,100). '.' .$file->guessClientExtension()));
            //Rename file with Storage facade
            ///dump(Storage::disk('local')->putFileAs('thumbnails', $file,random_int(1,100). '.' .$file->guessClientExtension()));

            /**
             * Rename file with storeAs()
             */
            //$name1= $file->storeAs('thumbnails', random_int(1,100). '.' .$file->guessClientExtension());
            /**
             * Rename file with Storage facade
             */
            //$name2= Storage::disk('local')->putFileAs('thumbnails', $file,random_int(1,100). '.' .$file->guessClientExtension());
            /**
             * génère l url pour l'accéder depuis l'extérieur de l'application public car le disk est public
             * Nb: le seul fichier accessible sur l'application depuis l'extérieur est le dossier public
             * alors pour acceder au dossier /storage il faut créer un lien symbolique depuis le dossier public vers le dossier storage
             * la commande pour créer le lien symbolique est : php artisan storage:link
             *
             */
            // dump(Storage::url($name1));
            /**
             * il faut mentionner que le fichier se trouve sur le disk local
             * génère un url accessible juste en local car le disk mentionné est local
             */
            //dump(Storage::disk('local')->url($name2));

        //}


        // $data = $request->only(['title','content']);
        $data = $request->validated();

        $data['user_id'] = $request->user()->id;
        $data['slug'] = Str::slug($data['title'], '-');
        $data['active'] = false;
        $data['user_id'] = Auth::id();
        $post = Post::create($data);
        // upload picture for current post
        if ($request->hasFile('picture')) {
            $path = $request->file('picture')->store('posts');
            $image = new Image(['path' => $path]);
            // associat post with his image
            $post->image()->save($image);

        }

        $request->session()->flash('status', 'post was created !');
        return redirect()->route('posts.index');
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        // if(Gate::denies('post.update',$post)){
        //     abort(403,"You can't edit this post");
        // }
        // $this->authorize("post.update",$post);
        $this->authorize("update", $post);

        return view('posts.edit', [
            'post' => $post
        ]);
    }

    public function update(StorePostRequest $request, $id)
    {
        $post = Post::findOrFail($id);

        // if(Gate::denies('post.update',$post)){
        //     abort(403,"You can't edit this post");
        // }
        // $this->authorize("post.update",$post);
        $this->authorize("update", $post);

        /**
         * update image
         */
        if ($request->hasFile('picture')) {
            $path = $request->file('picture')->store('posts');
            // if this post already have an image
            if($post->image){
                Storage::delete($post->image->path);
                $post->image->path = $path;
                $post->image->save();
            }else{
                //$post->image()->save(new Image(['path'=>$path]));
                $post->image()->save(Image::make(['path'=>$path]));
            }
        }

        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->slug = Str::slug($request->input('title'), '-');

        $post->save();

        $request->session()->flash('status', 'post was updated !');
        return redirect()->route('posts.index');
    }

    public function destroy(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        // if(Gate::denies('post.update',$post)){
        //     abort(403,"You can't edit this post");
        // }
        $this->authorize("delete", $post);

        $post->delete();

        //  Post::destroy($id);
        $request->session()->flash('status', 'post was deleted !');
        return redirect()->route('posts.index');
    }

    public function restore($id)
    {
        $post = Post::onlyTrashed()
            ->where('id', $id)
            ->first();

        $this->authorize("restore", $post);
        $post->restore();

        return redirect()->back();
    }

    public function forcedelete($id)
    {
        $post = Post::onlyTrashed()
            ->where('id', $id)
            ->first();
        $this->authorize("forcedelete", $post);
        $post->forceDelete();

        return redirect()->back();
    }
}
