<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class PostController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth')->only(['create','edit','update','destroy']);
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
        $posts = Post::withCount('comments')->get();
        return view('posts.index',[
            'posts'=> $posts,
            'mostCommented'=> Post::mostCommented()->take(5)->get(),
            'mostUsersActive'=> User::mostUsersActive()->take(5)->get(),
            'mostUsersActiveInLastMonth'=> User::mostUsersActiveInLastMonth()->take(5)->get(),
            'tab' =>'list'
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
        return view('posts.index',[
            'posts'=> $posts,
            'tab' =>'archive'
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
        return view('posts.index',[
            'posts'=> $posts,
            'tab' =>'all'
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
        return view('posts.show',[
            'post'=> Post::withCount('comments')->find($id)
        ]);
    }

    public function create(){
        //$this->authorize("post.create");
       $this->authorize("create",Post::class);
        return view('posts.create');
    }

    public function store(StorePostRequest $request){
       // $data = $request->only(['title','content']);
        $data = $request->validated();

        $data['user_id']=$request->user()->id;
        $data['slug']= Str::slug($data['title'],'-');
        $data['active'] = false;
        $data['user_id'] = Auth::id();
         $post = Post::create($data);


        $request->session()->flash('status','post was created !');
        return redirect()->route('posts.index');
    }

    public function edit($id){
        $post = Post::findOrFail($id);
        // if(Gate::denies('post.update',$post)){
        //     abort(403,"You can't edit this post");
        // }
       // $this->authorize("post.update",$post);
        $this->authorize("update",$post);

        return view('posts.edit',[
            'post'=> $post
        ]);
    }

    public function update(StorePostRequest $request,$id){
        $post = Post::findOrFail($id);

        // if(Gate::denies('post.update',$post)){
        //     abort(403,"You can't edit this post");
        // }
       // $this->authorize("post.update",$post);
        $this->authorize("update",$post);

        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->slug = Str::slug($request->input('title'),'-') ;

        $post->save();

        $request->session()->flash('status','post was updated !');
        return redirect()->route('posts.index');
    }

    public function destroy(Request $request,$id)
    {
        $post = Post::findOrFail($id);

        // if(Gate::denies('post.update',$post)){
        //     abort(403,"You can't edit this post");
        // }
        $this->authorize("delete",$post);

        $post->delete();

      //  Post::destroy($id);
        $request->session()->flash('status','post was deleted !');
        return redirect()->route('posts.index');
    }

    public function restore($id){
        $post = Post::onlyTrashed()
        ->where('id',$id)
        ->first();

        $this->authorize("restore",$post);
        $post->restore();

        return redirect()->back();
    }

    public function forcedelete($id){
        $post = Post::onlyTrashed()
        ->where('id',$id)
        ->first();
        $this->authorize("forcedelete",$post);
        $post->forceDelete();

        return redirect()->back();
    }

}
