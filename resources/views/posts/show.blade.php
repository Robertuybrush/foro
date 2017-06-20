@extends('layouts.app')

@section('content')
    <h1>{{ $post->title }}</h1>
    <p>{{ $post->user->name  }}</p>
    {!! $post->safe_html_content !!}

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
            {!! $comment->safe_html_content !!}

            <?php /*@can('accept',$comment)*/ ?>
            @if(Gate::allows('accept',$comment) && !$comment->answer)
                {!! Form::open(['route' => ['comments.accept', $comment], 'method' => 'POST']) !!}
                    <button type="submit">Aceptar respuesta</button>
                {!! Form::close() !!}
            @endif
            <?php /*@endcan*/ ?>
        </article>
    @endforeach
    {{ $comments->links() }}
@endsection