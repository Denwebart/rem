@extends('layouts.main')

@section('content')
    <ol class="breadcrumb">
        <li class="home-page">
            <a href="{{ URL::to('/') }}">
                <i class="material-icons">home</i>
            </a>
        </li>
        <li>
            <a href="{{ URL::to($page->parent->getUrl()) }}">
                {{ $page->parent->getTitle() }}
            </a>
        </li>
        <li>
            <a href="{{ URL::route('user.journal', ['journalAlias' => Config::get('settings.journalAlias'), 'login' => $user->getLoginForUrl()]) }}">
                {{ $user->login }}
            </a>
        </li>
        <li class="hidden-md hidden-xs">{{ $page->getTitleForBreadcrumbs() }}</li>
    </ol>

    <section id="content" class="well">

        <div class="row">
            <div class="col-lg-9 col-md-12 col-sm-9 col-xs-12">
                <h2>{{ $page->title }}</h2>
            </div>
            <div class="col-lg-3 col-md-12 col-sm-3 col-xs-12">
                {{-- Рейтинг --}}
                @include('widgets.rating')

                <div class="date pull-left hidden-lg hidden-md hidden-sm" title="Дата публикации">
                    <i class="material-icons pull-left">today</i>
                    <span class="pull-left">{{ DateHelper::dateFormat($page->published_at) }}</span>
                </div>
            </div>
        </div>

        <div class="page-info">
            <div class="pull-left">
                <div class="user pull-left">
                    <a href="{{ URL::route('user.profile', ['login' => $page->user->getLoginForUrl()]) }}">
                        {{ $page->user->getAvatar('mini', ['width' => '25px', 'class' => 'pull-left']) }}
                        <span class="login pull-left hidden-xs">{{ $page->user->login }}</span>
                    </a>
                </div>
                <div class="date pull-left hidden-xs" title="Дата публикации">
                    <i class="material-icons">today</i>
                    <span>{{ DateHelper::dateFormat($page->published_at) }}</span>
                </div>
            </div>

            <div class="pull-right">
                <div class="views pull-left" title="Количество просмотров">
                    <i class="material-icons">visibility</i>
                    <span>{{ $page->views }}</span>
                </div>
                <div class="comments-count pull-left" title="Количество комментариев">
                    <i class="material-icons">chat_bubble</i>
                    <a href="#comments">
                    <span class="count-comments">
                        {{ count($page->publishedComments) }}
                    </span>
                    </a>
                </div>
                <!-- Сохранение страницы в сохраненное -->
                @include('widgets.savedPages')
            </div>
        </div>

        {{ $areaWidget->contentTop() }}

        <div class="content">
            @if($page->image)
                <a class="fancybox" rel="group-content" href="{{ $page->getImageLink('origin') }}">
                    {{ $page->getImage('origin') }}
                </a>
            @endif
            {{ $page->getContentWithWidget() }}

            @if(count($page->tags))
                <div class="clearfix"></div>
                <ul class="tags">
                    @foreach($page->tags as $tag)
                        <li>
                            <a href="{{ URL::route('journal.tag', ['journalAlias' => Config::get('settings.journalAlias'), 'tag' => $tag->title]) }}" title="{{ $tag->title }}" class="tag btn btn-sm btn-info">
                                {{ $tag->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif

            <div class="clearfix"></div>
            <div class="margin-top-10">
                @include('widgets.sidebar.socialButtons')
            </div>
        </div>

        <!-- Подписка на журнал пользователя ("Подписки") -->
        @if(Auth::check())
            @if(!Auth::user()->is($user))
                <div class="clearfix"></div>
                @include('widgets.subscribe', [
                    'subscriptionObject' => $page->user,
                    'subscriptionField' => Subscription::FIELD_JOURNAL_ID,
                    'subscribeButtonTitle' => 'Подписаться на журнал',
                    'unsubscribeButtonTitle' => 'Отменить подписку на журнал',
                ])
            @endif
        @endif

        {{ $areaWidget->contentMiddle() }}

        {{-- Читайте также --}}
        <?php $relatedWidget = app('RelatedWidget') ?>
        {{ $relatedWidget->articles($page) }}
        {{ $relatedWidget->questions($page) }}

        {{-- Комментарии --}}
        <div id="comments">
            <?php $commentWidget = app('CommentWidget'); ?>
            {{ $commentWidget->show($page) }}
        </div>

        {{ $areaWidget->contentBottom() }}

    </section>
@stop
