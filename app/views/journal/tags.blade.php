@extends('layouts.main')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('/') }}">Главная</a></li>
        <li><a href="{{ URL::to($page->parent->getUrl()) }}">{{ $page->parent->getTitle() }}</a></li>
        <li>{{ $page->getTitle() }}</li>
    </ol>

    <section id="content" class="well">

        @if($page->title)
            <h2>{{ $page->title }}</h2>
        @endif
        @if($page->content)
            <div class="content">
                {{ $page->content }}
            </div>
        @endif

        @if(count($tags))
            <section id="tags-area">
                <ul>
                    @foreach($tags as $tag)
                        <li>
                            <a href="{{ URL::route('journal.tag', ['journalAlias' => $journalAlias, 'tag' => $tag->title]) }}">
                                {{ $tag->getImage(null, ['width' => '20px']) }}
                                {{ $tag->title }}
                                ({{ count($tag->pages) }})
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section><!--blog-area-->
        @endif

    </section>
@stop
