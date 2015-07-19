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

        <p>Найдено статей: {{ count($tag->pages) }}</p>

        {{ $areaWidget->contentMiddle() }}

        @if(count($tag->pages))
            <section id="blog-area">
                @foreach($articles as $article)
                    @include('journal.articleInfo')
                @endforeach
                <div>
{{--                    {{ $pages->links() }}--}}
                </div>
            </section><!--blog-area-->
        @endif

        {{ $areaWidget->contentBottom() }}

    </section>
@stop
