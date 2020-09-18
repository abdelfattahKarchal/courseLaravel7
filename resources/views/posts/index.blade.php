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
            {{-- @component('partials.badge')
                new
            @endcomponent --}}

            {{-- appel badge component qui se trouve sur App/Views/Components/badge --}}
            <x-badge>
                new
            </x-badge>
        @else
            {{-- @component('partials.badge',['type'=>'dark'])
                old
            @endcomponent --}}
            {{-- appel badge component qui se trouve sur App/Views/Components/badge --}}
            <x-badge type="dark">
                old
            </x-badge>
        @endif

        {{-- n est pas professionnel --}}
        {{-- <img src="http://localhost:8000/storage/{{$post->image->path ?? null}}" class="mt-3 img-fluid rounded" alt=""> --}}
         {{-- c'est une méthode professionnel --}}
        {{-- <img src="{{Storage::url($post->image->path ?? null) }}" class="mt-3 img-fluid rounded" alt=""> --}}
        {{-- la meilleur pratique --}}
        @if ($post->image)
            <img src="{{$post->image->url()}}" class="mt-3 img-fluid rounded" alt="">
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



        <x-tags :tags="$post->tags"></x-tags>
            {{-- <em> {{$post->created_at}} </em> --}}
            @if($post->comments_count)
            <div>
                {{-- @component('partials.badge')
                    {{$post->comments_count}} comments
                @endcomponent --}}

                <x-badge>
                    {{$post->comments_count}} comments
                </x-badge>
            </div>
            @else
            <div>
                {{-- @component('partials.badge',['type'=>'dark'])
                    no comments yet
                @endcomponent --}}
                <x-badge type="dark">
                    no comments yet
                </x-badge>
            </div>
            @endif
             {{-- <p class="text-muted">
                 {{$post->updated_at->diffForHumans()}}, by {{$post->user->name}}
             </p> --}}

             {{-- utilisation du component (updated) dynamique et pour le faire on précéde les attributs(date,name) par (:)
             pour qu on puisse savoire que c'est une variable --}}
             <x-updated :date="$post->created_at" :name="$post->user->name"></x-updated>
             <x-updated :date="$post->updated_at">Updated</x-updated>
             @auth
                @can('update', $post)
                    <a class="btn btn-warning btn-sm" href="{{route('posts.edit',['post'=>$post->id])}}">Edit</a>
                @endcan

                @cannot('delete', $post)
                {{-- <span class="badge badge-danger"> You can't delete this post</span> --}}
                    {{-- @component('partials.badge',['type' => 'danger'])
                        You can't delete this post
                    @endcomponent --}}
                    <x-badge type="danger">
                        You can't delete this post
                    </x-badge>
                @endcannot
                @if (!$post->deleted_at)
                    @can('delete', $post)
                        <form class="form-inline" method="POST" action="{{route('posts.destroy',['post'=>$post->id])}}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"  type="submit">Delete</button>
                        </form>
                    @endcan
                @else
                    @can('restore', $post)
                        <form class="form-inline" method="POST" action="{{url('/posts/'.$post->id.'/restore')}}">
                            @csrf
                            {{-- PATCH pour faire une modification partiel --}}
                            @method('PATCH')
                            <button class="btn btn-success btn-sm"  type="submit">Restore !</button>
                        </form>
                    @endcan
                    @can('forceDelete', $post)
                        <form class="form-inline" method="POST" action="{{url('/posts/'.$post->id.'/forcedelete')}}">
                            @csrf
                            {{-- PATCH pour faire une modification partiel --}}
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"  type="submit">Force delete !</button>
                        </form>
                    @endcan

                @endif
            @endauth

        </li>
    @empty
    {{-- <span class="badge-danger"></span> --}}
    {{-- @component('partials.badge',['type'=>'danger'])
        Not post
    @endcomponent --}}
        <x-badge type="danger">
            empty post
        </x-badge>
    @endforelse

</ul>
    </div>
     {{-- cards --}}
    <div class="col-4">
       @include('posts.sidebar')
    </div>
    {{-- end cards --}}
</div>



@endsection
