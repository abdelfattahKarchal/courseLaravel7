@extends('layouts.app')

@section('content')

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


@endsection
