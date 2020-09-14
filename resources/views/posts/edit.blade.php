@extends('layouts.app')

@section('content')

<h1>Edit post</h1>
<form method="POST" action="{{route('posts.update',['post'=>$post->id])}}" enctype="multipart/form-data">
    @csrf
    @method('PUT');
    @include('posts.form')


    <button class="btn btn-block btn-warning" type="submit">Update post</button>
</form>
@endsection
