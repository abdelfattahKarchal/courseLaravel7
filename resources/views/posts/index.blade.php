@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-8">
        <h1>List of posts</h1>

{{-- <nav class="nav nav-tabs nav-stacked my-5">
    <a class="nav-link @if($tab=='list') active @endif" href="/posts">List</a>
    <a class="nav-link @if($tab=='archive') active @endif" href="/posts/archive">Archive</a>
    <a class="nav-link @if($tab=='all') active @endif" href="/posts/all">All</a>
</nav> --}}
<div class="my-3">
    <h4>{{ $posts->count() }} post(s)</h4>
</div>

<ul class="list-group-item">
    @forelse ($posts as $post)
    <li class="list-group-item">
        @if($post->created_at->diffInHours() < 1)
        {{-- par defaut si on passe pas un paramèttre (tableau) sur componnent par defaut prendre la valeur success
        sur la vue bade.blade.php --}}
            @component('partials.badge')
                new
            @endcomponent
        @else
            @component('partials.badge',['type'=>'dark'])
                old
            @endcomponent
        @endif
        <h2>
            <a href=" {{route('posts.show',['post'=>$post->id])}} ">
                @if($post->trashed())
                <del>
                    {{$post->title}}
                </del>
                @else
                {{$post->title}}
                @endif
            </a>
            </h2>
            {{-- <em> {{$post->created_at}} </em> --}}
            @if($post->comments_count)
            <div>
                @component('partials.badge')
                    {{$post->comments_count}} comments
                @endcomponent
            </div>
            @else
            <div>
                @component('partials.badge',['type'=>'dark'])
                    no comments yet
                @endcomponent
            </div>
            @endif
             <p class="text-muted">
                 {{$post->updated_at->diffForHumans()}}, by {{$post->user->name}}
             </p>
             @can('update', $post)
                <a class="btn btn-warning" href="{{route('posts.edit',['post'=>$post->id])}}">Edit</a>
             @endcan

             @cannot('delete', $post)
                @component('partials.badge',['type' => 'danger'])
                    You can't delete this post
                @endcomponent
                {{-- <span class="badge badge-danger"> You can't delete this post</span> --}}
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
                @can('forceDelete', $post)
                    <form class="form-inline" method="POST" action="{{url('/posts/'.$post->id.'/forcedelete')}}">
                        @csrf
                        {{-- PATCH pour faire une modification partiel --}}
                        @method('DELETE')
                        <button class="btn btn-danger"  type="submit">Force delete !</button>
                    </form>
                @endcan

            @endif


        </li>
    @empty
    @component('partials.badge',['type'=>'danger'])
        Not post
    @endcomponent
        {{-- <span class="badge-danger"></span> --}}
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
                            @component('partials.badge',['type'=>'success'])
                                {{$post->comments_count}}
                            @endcomponent
                            {{-- <span class="badge badge-success">
                                {{$post->comments_count}}
                            </span> --}}
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
                        @component('partials.badge',['type'=>'info'])
                            {{$user->posts_count}}
                        @endcomponent
                        {{-- <span class="badge badge-info"> {{$user->posts_count}} </span> --}}
                        {{ $user->name }}
                    </li>
                @endforeach ($mostUsersActive as $user)
            </ul>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <h4 class="card-title">Most users</h4>
                <p class="text-muted"> Most Users active in last month</p>
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($mostUsersActiveInLastMonth as $user)
                    <li class="list-group-item">
                        @component('partials.badge',['type'=>'info'])
                            {{$user->posts_count}}
                        @endcomponent
                        {{-- <span class="badge badge-info"> {{$user->posts_count}} </span> --}}
                        {{ $user->name }}
                    </li>
                @endforeach ($mostUsersActive as $user)
            </ul>
        </div>

    </div>
</div>



@endsection
