@extends('layouts.app')

@section('content')
    <h1>{{ $post->title }}</h1>
    <p>{{ $post->user->name  }}</p>
    <p>{{ $post->content }}</p>
@endsection