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
            <section id="questions-area" class="blog">
                <div class="count">
                    Показано: <span>{{ $questions->count() }}</span>.
                    Всего: <span>{{ $questions->getTotal() }}</span>.
                </div>
                @foreach($questions as $key => $question)
                    @if(0 != $key)
                        <hr>
                    @endif
                    @include('site.questionInfo')
                @endforeach
                {{ $questions->links() }}
            </section><!--blog-area-->
        @endif

        {{ $areaWidget->contentBottom() }}

    </section>
@stop
