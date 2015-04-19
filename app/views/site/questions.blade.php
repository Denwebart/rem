@extends('layouts.main')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('/') }}">Главная</a></li>
        @if($page->parent)
            <li>
                <a href="{{ URL::to($page->parent->alias) }}">
                    {{ $page->parent->getTitle() }}
                </a>
            </li>
        @endif
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
                    <a href="{{ URL::route('user.questions.create', ['login' => Auth::user()->getLoginForUrl()]) }}" class="btn btn-success pull-right">
                        Задать вопрос
                    </a>
                </div>
            </div>
        @endif

        @if(count($page->publishedChildren))
            <section id="blog-area">
                @foreach($questions as $question)
                    <div class="row">
                        <div class="col-md-12">
                            <h3>
                                <a href="{{ URL::to($question->getUrl()) }}">
                                    {{ $question->title }}
                                </a>
                            </h3>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ URL::route('user.profile', ['ligin' => $question->user->getLoginForUrl()]) }}">
                                {{ $question->user->getAvatar('mini') }}
                                {{ $question->user->login }}
                            </a>
                        </div>
                        <div class="col-md-8">
                            <div class="pull-right">
                                <a href="{{ URL::to($question->parent->getUrl()) }}">
                                    {{ $question->parent->getTitle() }}
                                </a>
                            </div>
                            <p>{{ $question->getIntrotext() }}</p>
                            <a class="pull-right" href="{{ URL::to($question->getUrl()) }}">
                                Читать полностью <span class="glyphicon glyphicon-chevron-right"></span>
                            </a>
                        </div>
                    </div>
                    <hr/>
                @endforeach
                <div>
                    {{ $questions->links() }}
                </div>
            </section><!--blog-area-->
        @endif

    </section>
@stop
