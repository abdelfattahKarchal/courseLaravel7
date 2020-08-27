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

             {{-- utilisation du component (updated) dynamique et pour le faire on prééde par les attributs(date,name) par (:)
             pour qu on puisse savoire que c'est une variable --}}
             <x-updated :date="$post->created_at" :name="$post->user->name"></x-updated>
             <x-updated :date="$post->updated_at">Updated</x-updated>
             @can('update', $post)
                <a class="btn btn-warning" href="{{route('posts.edit',['post'=>$post->id])}}">Edit</a>
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
        {{-- card 1 Most post Commented --}}
        {{-- <div class="card">
            <div class="card-body">
                <h4 class="card-title">Most Commented</h4>
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($mostCommented as $post)
                     <li class="list-group-item">
                     <a href="{{ route('posts.show',['post'=>$post->id]) }}">{{$post->title}}</a>
                        <p>
                            {{-- <span class="badge badge-success">
                                {{$post->comments_count}}
                            </span> --}}
                            {{-- @component('partials.badge',['type'=>'success'])
                                {{$post->comments_count}}
                            @endcomponent --}}
                            {{-- <x-badge>
                                {{$post->comments_count}}
                            </x-badge>
                        </p>
                    </li>
                @endforeach ($posts as $post)
            </ul>
        </div>  --}}
 {{-- utilisation du component card on remplacant le code en haut par ce lui ci en bas
    j'ai ajouté le slot pour la 1ere card car possède de contenu different (pas de text et possède d'un <a href>)  --}}
        <x-card title="Most Commented">
            @foreach ($mostCommented as $post)
                <li class="list-group-item">
                    <a href="{{ route('posts.show',['post'=>$post->id]) }}">{{$post->title}}</a>
                    <p>
                        {{-- <span class="badge badge-success">
                            {{$post->comments_count}}
                        </span> --}}
                        {{-- @component('partials.badge',['type'=>'success'])
                            {{$post->comments_count}}
                        @endcomponent --}}
                        <x-badge>
                            {{$post->comments_count}}
                        </x-badge>
                    </p>
                </li>
            @endforeach ($posts as $post)
        </x-card>
        {{-- end card 1 Most post Commented --}}
        {{-- ------------------------------------------------------------------------------------------------------ --}}

        {{-- card 2 Most users post written --}}
        {{-- <div class="card mt-4">
            <div class="card-body">
                <h4 class="card-title">Most users</h4>
                <p class="text-muted"> Most Users post written</p>
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($mostUsersActive as $user)
                    <li class="list-group-item">
                        {{-- <span class="badge badge-info"> {{$user->posts_count}} </span> --}}
                        {{-- @component('partials.badge',['type'=>'info'])
                            {{$user->posts_count}}
                        @endcomponent --}}
                        {{-- <x-badge type="info">
                            {{$user->posts_count}}
                        </x-badge>
                        {{ $user->name }}
                    </li>
                @endforeach ($mostUsersActive as $user)
            </ul>
        </div> --}}

        {{-- utilisation du component card on remplacant le code en haut par ce lui ci en bas  --}}
        <x-card
            title="Most users"
            text="Most Users post written"
            {{-- :items="collect($mostUsersActive)->pluck('name')" --}}
            :items="collect($mostUsersActive)"
        ></x-card>
        {{-- end card 2 Most users post written --}}
{{-- ------------------------------------------------------------------------------------------------------ --}}
        {{-- card 3  Most Users active in last month --}}
        {{-- <div class="card mt-4">
            <div class="card-body">
                <h4 class="card-title">Most users</h4>
                <p class="text-muted"> Most Users active in last month</p>
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($mostUsersActiveInLastMonth as $user)
                    <li class="list-group-item">
                        {{-- <span class="badge badge-info"> {{$user->posts_count}} </span> --}}
                        {{-- @component('partials.badge',['type'=>'info'])
                            {{$user->posts_count}}
                        @endcomponent --}}
                        {{-- <x-badge type="info">
                            {{$user->posts_count}}
                        </x-badge>
                        {{ $user->name }}
                    </li>
                @endforeach ($mostUsersActive as $user)
            </ul>
        </div> --}}


        <x-card
            title="Most users"
            text="Most Users active in last month"
            {{-- items="collect($mostUsersActiveInLastMonth)->pluck('name')" --}}
            :items="collect($mostUsersActiveInLastMonth)"
    ></x-card>
        {{-- end card 3  Most Users active in last month --}}

    </div>
    {{-- end cards --}}
</div>



@endsection
