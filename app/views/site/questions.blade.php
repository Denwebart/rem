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
                @foreach($page->publishedChildren()->orderBy('published_at', 'ASC')->get() as $child)
                    <div class="row">
                        <div class="col-md-12">
                            <h3>
                                <a href="{{ URL::to($child->getUrl()) }}">
                                    {{ $child->getTitle() }}
                                </a>
                            </h3>
                        </div>
                    </div>
                @endforeach
            </section><!--blog-area-->
        @endif

    </section>
@stop
