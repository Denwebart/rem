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
        @if($page->content)
            <div class="content">
                {{ $page->content }}
            </div>
        @endif

        @if(Auth::check())
            <div class="row">
                <div class="col-md-12">
                    <a href="{{ URL::route('user.journal.create', ['login' => Auth::user()->getLoginForUrl()]) }}" class="btn btn-success pull-right">
                        Написать статью
                    </a>
                </div>
            </div>
        @endif

        @if(count($page->publishedChildren))
            <section id="blog-area">
                @foreach($articles as $article)
                    <div class="row">
                        <div class="col-md-12">
                            <h3>
                                <a href="{{ URL::to($article->getUrl()) }}">
                                    {{ $article->title }}
                                </a>
                            </h3>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ URL::route('user.profile', ['ligin' => $article->user->getLoginForUrl()]) }}">
                                {{ $article->user->getAvatar('mini') }}
                                {{ $article->user->login }}
                            </a>
                        </div>
                        <div class="col-md-8">
                            <div class="pull-right">
                                @if(Auth::check())
                                    @if($article->user_id == Auth::user()->id)
                                        <a href="{{ URL::route('user.journal.edit', ['login' => Auth::user()->getLoginForUrl(),'id' => $article->id]) }}" class="btn btn-info">
                                            Редактировать
                                        </a>
                                    @endif
                                @endif
                                <a href="{{ URL::to($article->parent->getUrl()) }}">
                                    {{ $article->parent->getTitle() }}
                                </a>
                            </div>
                            <p>{{ $article->getIntrotext() }}</p>

                            <ul class="tags">
                                @foreach($article->tags as $tag)
                                    <li>
                                        <a href="{{ URL::route('search', ['tag' => $tag->title]) }}" title="{{ $tag->title }}">
                                            {{ $tag->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                            <a class="pull-right" href="{{ URL::to($article->getUrl()) }}">
                                Читать полностью <span class="glyphicon glyphicon-chevron-right"></span>
                            </a>
                        </div>
                    </div>
                    <hr/>
                @endforeach
                <div>
                    {{ $articles->links() }}
                </div>
            </section><!--blog-area-->
        @endif

    </section>
@stop
