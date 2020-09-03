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
                <x-updated :date="$comment->created_at" :name="$comment->user->name"></x-updated>
                {{-- {{ $comment->created_at->diffForHumans() }} --}}
            </p>
        @empty
        <div>
            <span class="badge-dark">no comments yet</span>
        </div>
        @endforelse
    </div>
    {{-- cards --}}
    <div class="col-4">
        @include('posts.sidebar')
    </div>
    {{-- end cards --}}

</div>




@endsection
