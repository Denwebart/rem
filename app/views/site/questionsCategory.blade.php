@extends('layouts.main')

@section('content')
    <ol class="breadcrumb">
        <li class="home-page">
            <a href="{{ URL::to('/') }}">
                <i class="material-icons">home</i>
            </a>
        </li>
        @if($page->parent)
            <li>
                <a href="{{ URL::to($page->parent->getUrl()) }}">
                    {{ $page->parent->getTitle() }}
                </a>
            </li>
        @endif
        <li class="hidden-md hidden-xs">{{ $page->getTitleForBreadcrumbs() }}</li>
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
                        {{ $page->getImage('origin') }}
                    </a>
                @endif
                {{ $page->getContentWithWidget() }}
            </div>
        @endif

        {{ $areaWidget->contentMiddle() }}

        @if(Auth::check())
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-6 col-lg-5 col-sm-offset-8 col-md-offset-6 col-lg-offset-7">
                    @if(Auth::user()->isAdmin())
                        <a href="{{ URL::route('admin.questions.create', ['backUrl' => urlencode(Request::url())]) }}" class="btn btn-success btn-sm btn-full pull-right">
                            Задать вопрос
                        </a>
                    @else
                        <a href="{{ URL::route('user.questions.create', ['login' => Auth::user()->getLoginForUrl(), 'category' => $page->id, 'backUrl' => urlencode(Request::url())]) }}" class="btn btn-success btn-sm btn-full pull-right">
                            Задать вопрос
                        </a>
                    @endif
                </div>
            </div>
        @endif

        @if(count($questions))
            <section id="questions-area" class="blog margin-top-10">
                <div class="count margin-bottom-20">
                    Показано: <span>{{ $questions->count() }}</span>.
                    Всего: <span>{{ $questions->getTotal() }}</span>.
                </div>
                @foreach($questions as $key => $question)
                    @if(0 != $key)
                        <hr/>
                    @endif
                    @include('site.questionInfo')
                @endforeach
                {{ $questions->links() }}
            </section><!--blog-area-->
        @endif

        {{ $areaWidget->contentBottom() }}

    </section>
@stop
