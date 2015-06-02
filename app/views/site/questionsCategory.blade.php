@extends('layouts.main')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('/') }}">Главная</a></li>
        @if($page->parent)
            <li>
                <a href="{{ URL::to($page->parent->getUrl()) }}">
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
                    <a href="{{ URL::route('user.questions.create', ['login' => Auth::user()->getLoginForUrl(), 'category' => $page->id]) }}" class="btn btn-success pull-right">
                        Задать вопрос
                    </a>
                </div>
            </div>
        @endif

        @if(count($questions))
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

                            <div class="answers">
                                Ответы:
                                @if(count($question->bestComments))
                                    <i class="mdi-action-done mdi-success" style="font-size: 20pt;"></i>
                                @endif
                                <a href="{{ URL::to($question->getUrl()) }}#answers">
                                    {{ count($question->publishedComments) }}
                                </a>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="pull-right">
                                @if(Auth::check())
                                    @if($question->user_id == Auth::user()->id)
                                        <a href="{{ URL::route('user.questions.edit', ['login' => Auth::user()->getLoginForUrl(),'id' => $question->id]) }}" class="btn btn-info">
                                            Редактировать
                                        </a>
                                    @endif
                                @endif
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
                @endforeach
                <div>
                    {{ $questions->links() }}
                </div>
            </section><!--blog-area-->
        @endif

    </section>
@stop
