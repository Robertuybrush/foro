@extends('layouts.app')

@section('content')
    <h1>{{ $post->title }}</h1>
    <p>{{ $post->user->name  }}</p>
    <p>{{ $post->content }}</p>

    <h4>Comentarios</h4>

    {!! Form::open(['route' => ['comments.store',$post], 'method' => 'POST']) !!}
        {!! Field::textarea('comment') !!}
        <button type="submit">
            Publicar comentario
        </button>
    {!! Form::close() !!}

    @foreach($comments as $comment)
        <article class="{{ $comment->answer ? 'answer' : '' }}">
            <span class="author">{{ $comment->user->name }}</span>
            {{ $comment->comment }}
            {!! Form::open(['route' => ['comments.accept', $comment], 'method' => 'POST']) !!}
                <button type="submit">Aceptar respuesta</button>
            {!! Form::close() !!}
        </article>
    @endforeach
    {{ $comments->links() }}
@endsection