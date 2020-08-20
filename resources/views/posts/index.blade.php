@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-8">
        <h1>List of posts</h1>

<nav class="nav nav-tabs nav-stacked my-5">
    <a class="nav-link @if($tab=='list') active @endif" href="/posts">List</a>
    <a class="nav-link @if($tab=='archive') active @endif" href="/posts/archive">Archive</a>
    <a class="nav-link @if($tab=='all') active @endif" href="/posts/all">All</a>
</nav>
<div class="my-3">
    <h4>{{ $posts->count() }} post(s)</h4>
</div>

<ul class="list-group-item">
    @forelse ($posts as $post)
    <li class="list-group-item">
        <h2>
            <a href=" {{route('posts.show',['post'=>$post->id])}} ">{{$post->title}}</a></h2>
            {{-- <em> {{$post->created_at}} </em> --}}
            @if($post->comments_count)
            <div>
                <span class="badge-success">{{$post->comments_count}} comments</span>
            </div>
            @else
            <div>
                <span class="badge-dark">no comments yet</span>
            </div>
            @endif
             <p class="text-muted">
                 {{$post->updated_at->diffForHumans()}}, by {{$post->user->name}}
             </p>
             @can('update', $post)
                <a class="btn btn-warning" href="{{route('posts.edit',['post'=>$post->id])}}">Edit</a>
             @endcan

             @cannot('delete', $post)
                <span class="badge badge-danger">You can't delete this post</span>
             @endcannot
            @if (!$post->deleted_at)
                @can('delete', $post)
                    <form class="form-inline" method="POST" action="{{route('posts.destroy',['post'=>$post->id])}}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger"  type="submit">Delete</button>
                    </form>
                @endcan
            @else
                @can('restore', $post)
                    <form class="form-inline" method="POST" action="{{url('/posts/'.$post->id.'/restore')}}">
                        @csrf
                        {{-- PATCH pour faire une modification partiel --}}
                        @method('PATCH')
                        <button class="btn btn-success"  type="submit">Restore !</button>
                    </form>
                @endcan
                @can('forcedelete', $post)
                    <form class="" method="POST" action="{{url('/posts/'.$post->id.'/forcedelete')}}">
                        @csrf
                        {{-- PATCH pour faire une modification partiel --}}
                        @method('DELETE')
                        <button class="btn btn-danger"  type="submit">Force delete !</button>
                    </form>
                @endcan

            @endif


        </li>
    @empty
        <span class="badge-danger">Not post</span>
    @endforelse

</ul>
    </div>
    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Most Commented</h4>
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($mostCommented as $post)
                     <li class="list-group-item">
                     <a href="{{ route('posts.show',['post'=>$post->id]) }}">{{$post->title}}</a>
                        <p>
                            <span class="badge badge-success">
                                {{$post->comments_count}}
                            </span>
                        </p>
                    </li>
                @endforeach ($posts as $post)
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <h4 class="card-title">Most users</h4>
                <p class="text-muted"> Most Users post written</p>
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($mostUsersActive as $user)
                    <li class="list-group-item">
                        <span class="badge badge-info"> {{$user->posts_count}} </span>
                        {{ $user->name }}
                    </li>
                @endforeach ($mostUsersActive as $user)
            </ul>
        </div>
    </div>
</div>



@endsection
