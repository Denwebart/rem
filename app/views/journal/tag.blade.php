@extends('layouts.main')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('/') }}">Главная</a></li>
        <li><a href="{{ URL::to($tags->parent->getUrl()) }}">{{ $tags->parent->getTitle() }}</a></li>
        <li><a href="{{ URL::to($tags->getUrl()) }}">{{ $tags->getTitle() }}</a></li>
        <li>{{ $page->title }}</li>
    </ol>

    <section id="content" class="well">

        <h2>{{ $page->title }}</h2>

        {{ $areaWidget->contentTop() }}

        @if($page->content)
            <div class="content">
                @if($page->image)
                    {{ $page->getImage() }}
                @endif
                {{ $page->getContentWithWidget() }}
            </div>
        @endif

        {{ $areaWidget->contentMiddle() }}

        @if(count($articles))
            <section id="blog-area" class="blog">
                <div class="count">
                    Показано статей: <span>{{ $articles->count() }}</span>.
                    Всего: <span>{{ $articles->getTotal() }}</span>.
                </div>

                @foreach($articles as $article)
                    @include('journal.articleInfo')
                @endforeach

                {{ $articles->links() }}
            </section><!--blog-area-->
        @endif

        {{ $areaWidget->contentBottom() }}

    </section>
@stop
