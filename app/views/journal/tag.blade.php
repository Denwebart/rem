@extends('layouts.main')

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li class="home-page">
            <a href="{{ URL::to('/') }}">
                <i class="material-icons">home</i>
            </a>
        </li>
        <li><a href="{{ URL::to($tagsParent->getUrl()) }}">{{ $tagsParent->getTitle() }}</a></li>
        <li><a href="{{ URL::to($tags->getUrl()) }}">{{ $tags->getTitle() }}</a></li>
        <li class="hidden-md hidden-xs">{{ $page->title }}</li>
    </ol>
@stop

@section('content')
    <section id="content" class="well">

        @if($tag->image)
            {{ $tag->getImage(null, ['width' => '50px', 'class' => 'pull-left margin-right-10']) }}
            <h2 class="pull-left margin-top-10">{{ $page->title }}</h2>
        @else
            <h2 class="pull-left">{{ $page->title }}</h2>
        @endif


        {{ $areaWidget->contentTop() }}

        @if($page->content)
            <div class="content">
                @if($page->image)
                    <a class="fancybox pull-left" rel="group-content" href="{{ $page->getImageLink('origin') }}">
                        {{ $page->getImage('origin', ['class' => 'page-image']) }}
                    </a>
                @endif
                {{ $page->getContentWithWidget() }}
            </div>
        @endif

        {{ $areaWidget->contentMiddle() }}

        @if(count($articles))
            <section id="articles-area" class="blog margin-top-10">
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
