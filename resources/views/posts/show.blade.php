@extends('layouts.app')

@section('content')
    <h1>{{ $post->title }}</h1>
    <p>{{ $post->user->name  }}</p>
    {!! $post->safe_html_content !!}

    @if(auth()->check())
        @if(!auth()->user()->isSubscribedTo($post))
            {!! Form::open(['route' => ['posts.subscribe',$post], 'method' => 'POST']) !!}
                <button type="submit">
                    Suscribirse al post
                </button>
            {!! Form::close() !!}
        @else
            {!! Form::open(['route' => ['posts.unsubscribe',$post], 'method' => 'POST']) !!}
                <button type="submit">
                    Desuscribirse del post
                </button>
            {!! Form::close() !!}
        @endif
    @endif

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