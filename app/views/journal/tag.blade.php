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
        <p>Найдено статей: {{ count($tag->pages) }}</p>
        
        @if(count($tag->pages))
            <section id="blog-area">
                @foreach($tag->pages as $page)
                    <div class="row">
                        <div class="col-md-12">
                            <h3>
                                <a href="{{ URL::to($page->getUrl()) }}">
                                    {{ $page->title }}
                                </a>
                            </h3>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ URL::route('user.profile', ['ligin' => $page->user->getLoginForUrl()]) }}">
                                {{ $page->user->getAvatar('mini') }}
                                {{ $page->user->login }}
                            </a>
                        </div>
                        <div class="col-md-8">
                            <div class="pull-right">
                                @if(Auth::check())
                                    @if($page->user_id == Auth::user()->id)
                                        <a href="{{ URL::route('user.journal.edit', ['login' => Auth::user()->getLoginForUrl(),'id' => $page->id]) }}" class="btn btn-info">
                                            Редактировать
                                        </a>
                                    @endif
                                @endif
                                <a href="{{ URL::to($page->parent->getUrl()) }}">
                                    {{ $page->parent->getTitle() }}
                                </a>
                            </div>
                            <p>{{ $page->getIntrotext() }}</p>

                            <ul class="tags">
                                @foreach($page->tags as $tag)
                                    <li>
                                        <a href="{{ URL::route('journal.tag', ['journalAlias' => $journalAlias, 'tag' => $tag->title]) }}" title="{{ $tag->title }}">
                                            {{ $tag->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                            <a class="pull-right" href="{{ URL::to($page->getUrl()) }}">
                                Читать полностью <span class="glyphicon glyphicon-chevron-right"></span>
                            </a>
                        </div>
                    </div>
                    <hr/>
                @endforeach
                <div>
{{--                    {{ $pages->links() }}--}}
                </div>
            </section><!--blog-area-->
        @endif

    </section>
@stop
