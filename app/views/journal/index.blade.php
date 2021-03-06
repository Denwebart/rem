@extends('layouts.main')

@section('breadcrumbs')
    <!-- Breadcrumbs -->
    @include('widgets.breadcrumbs', ['items' => [
        [
            'title' => $page->getTitleForBreadcrumbs()
        ]
    ]])
@stop

@section('content')
    <section id="content" class="well">

        @if(!Request::has('stranitsa') || Request::get('stranitsa') == 1)
            <div itemscope itemtype="http://schema.org/Article">
                <meta itemprop="datePublished" content="{{ DateHelper::dateFormatForSchema($page->published_at) }}">
                @if($page->title)
                    <h2 itemprop="headline">{{ $page->title }}</h2>
                @else
                    <meta itemprop="headline" content="{{ $page->getTitle() }}">
                @endif

                {{ $areaWidget->contentTop() }}

                @if($page->content)
                    <div class="content" itemprop="articleBody">
                        @if($page->image)
                            <a class="fancybox pull-left" data-fancybox-group="group-content" href="{{ $page->getImageLink('origin') }}">
                                {{ $page->getImage('origin', ['class' => 'page-image']) }}
                            </a>
                        @else
                            <meta itemprop="image" content="{{ URL::to(Config::get('settings.defaultImage')) }}">
                        @endif
                        {{ $page->getContentWithWidget() }}
                    </div>
                @endif

                {{ $areaWidget->contentMiddle() }}
            </div>
        @endif

        @if(Auth::check())
            @if(!Ip::isBanned())
                @if(!Auth::user()->is_banned)
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-7 col-lg-5 col-sm-offset-8 col-md-offset-5 col-lg-offset-7">
                            @if(Auth::user()->isAdmin())
                                <a href="{{ URL::route('admin.articles.create', ['backUrl' => urlencode(Request::url())]) }}" class="btn btn-success btn-sm btn-full pull-right">
                                    Написать статью
                                </a>
                            @else
                                <a href="{{ URL::route('user.journal.create', ['login' => Auth::user()->getLoginForUrl(), 'backUrl' => urlencode(Request::url())]) }}" class="btn btn-success btn-sm btn-full pull-right">
                                    Написать статью
                                </a>
                            @endif
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
            <section id="articles-area" class="blog margin-top-10">
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