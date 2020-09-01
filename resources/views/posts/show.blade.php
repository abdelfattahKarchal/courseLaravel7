@extends('layouts.app')

@section('content')

<div class="row">
    <div class= "col-8">
        <h1>{{$post->title}}</h1>
        <p>{{$post->content}}</p>

        <x-tags :tags="$post->tags"></x-tags>

        <em> Added {{$post->created_at->diffForHumans()}} </em>

        @if ((new Carbon\Carbon())->diffInMinutes($post->created_at) < 5)
            <strong> New!</strong>
        @endif

        <h3>Comments :</h3>

        @forelse ($post->comments as $comment)
            <p>
                {{$comment->content}}
            </p>
            <p class="text-muted">
                {{ $comment->created_at->diffForHumans() }}
            </p>
        @empty
        <div>
            <span class="badge-dark">no comments yet</span>
        </div>
        @endforelse
    </div>
          {{-- cards --}}
    <div class="col-4">
        {{-- card 1 Most post Commented --}}
        <x-card title="Most Commented">
            @foreach ($mostCommented as $post)
                <li class="list-group-item">
                    <a href="{{ route('posts.show',['post'=>$post->id]) }}">{{$post->title}}</a>
                    <p>
                        <x-badge>
                            {{$post->comments_count}}
                        </x-badge>
                    </p>
                </li>
            @endforeach ($posts as $post)
        </x-card>
        {{-- end card 1 Most post Commented --}}

        {{-- card 2 Most users post written --}}
        <x-card
            title="Most users"
            text="Most Users post written"
            {{-- :items="collect($mostUsersActive)->pluck('name')" --}}
            :items="collect($mostUsersActive)"
        ></x-card>
        {{-- end card 2 Most users post written --}}

        {{-- card 3  Most Users active in last month --}}
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
