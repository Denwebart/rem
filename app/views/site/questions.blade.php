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
        <li>{{ $page->getTitleForBreadcrumbs() }}</li>
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
            <div class="row">
                <div class="col-md-12">
                    @if(Auth::user()->isAdmin())
                        <a href="{{ URL::route('admin.questions.create') }}" class="btn btn-success pull-right">
                            Задать вопрос
                        </a>
                    @else
                        <a href="{{ URL::route('user.questions.create', ['login' => Auth::user()->getLoginForUrl()]) }}" class="btn btn-success pull-right">
                            Задать вопрос
                        </a>
                    @endif
                </div>
            </div>
        @endif

        @if(count($page->publishedChildren))
            <section id="questions-area" class="blog margin-top-10">
                <div class="count margin-bottom-20">
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
