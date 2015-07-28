@extends('layouts.main')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('/') }}">Главная</a></li>
        <li>{{ $page->getTitle() }}</li>
    </ol>

    <section id="content" class="well">

        @if($page->title)
            <h2>{{ $page->title }}</h2>
        @endif

        {{ $areaWidget->contentTop() }}

        @if($page->content)
            <div class="content">
                @if($page->image)
                    <a class="fancybox" rel="group-content" href="{{ $page->getImageLink('origin') }}">
                        {{ $page->getImage() }}
                    </a>
                @endif
                {{ $page->getContentWithWidget() }}
            </div>
        @endif

        {{ $areaWidget->contentMiddle() }}

        @if(Auth::check())
            @if(!Ip::isBanned())
                @if(!Auth::user()->is_banned)
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{ URL::route('user.journal.create', ['login' => Auth::user()->getLoginForUrl()]) }}" class="btn btn-success pull-right">
                                Написать статью
                            </a>
                        </div>
                    </div>
                @else
                    @include('cabinet::user.banMessage')
                @endif
            @else
                @include('messages.bannedIp')
            @endif
        @endif

        @if(count($page->publishedChildren))
            <section id="articles-area" class="blog">
                <div class="count">
                    Показано статей: <span>{{ $articles->count() }}</span>.
                    Всего: <span>{{ $articles->getTotal() }}</span>.
                </div>
                @foreach($articles as $key => $article)
                    @if(0 != $key)
                        <hr/>
                    @endif
                    @include('journal.articleInfo')
                @endforeach
                {{ $articles->links() }}
            </section><!--blog-area-->
        @endif

        {{ $areaWidget->contentBottom() }}

    </section>
@stop