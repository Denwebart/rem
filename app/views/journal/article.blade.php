@extends('layouts.main')

@section('breadcrumbs')
    <!-- Breadcrumbs -->
    @include('widgets.breadcrumbs', ['items' => [
        [
            'title' => ($page->parent->menuItem) ? $page->parent->menuItem->menu_title : $page->parent->getTitle(),
            'url' => URL::to($page->parent->getUrl())
        ],
        [
            'title' => $user->login,
            'url' => URL::route('user.journal', ['journalAlias' => Config::get('settings.journalAlias'), 'login' => $user->getLoginForUrl()])
        ],
        [
            'title' => $page->getTitleForBreadcrumbs()
        ]
    ]])
@stop

@section('content')
    <section id="content" class="well" itemscope itemtype="http://schema.org/Article">

        <div class="row">
            <div class="col-lg-9 col-md-12 col-sm-9 col-xs-12">
                <h2 itemprop="headline">{{ $page->title }}</h2>
            </div>
            <div class="col-lg-3 col-md-12 col-sm-3 col-xs-12">
                {{-- Рейтинг --}}
                @include('widgets.rating')

                <div class="date pull-left hidden-lg hidden-md hidden-sm">
                    <i class="material-icons pull-left">today</i>
                    <span class="pull-left">{{ DateHelper::dateFormat($page->published_at) }}</span>
                </div>
            </div>
        </div>

        <div class="page-info">
            <div class="pull-left">
                <div class="user pull-left" itemprop="author" itemscope itemtype="http://schema.org/Person">
                    <a href="{{ URL::route('user.profile', ['login' => $page->user->getLoginForUrl()]) }}" itemprop="url">
                        {{ $page->user->getAvatar('mini', ['width' => '25', 'class' => 'pull-left']) }}
                        <span class="login pull-left hidden-xs" itemprop="name">{{ $page->user->login }}</span>
                    </a>
                </div>
                <div class="date pull-left hidden-xs">
                    <i class="material-icons">today</i>
                    <time datetime="{{ DateHelper::dateFormatForSchema($page->published_at) }}" itemprop="datePublished">
                        {{ DateHelper::dateFormat($page->published_at) }}
                    </time>
                </div>
            </div>

            <div class="pull-right">
                <div class="views pull-left" title="Количество просмотров" data-toggle="tooltip" data-placement="bottom">
                    <i class="material-icons">visibility</i>
                    <span>{{ $page->views }}</span>
                </div>
                <div class="comments-count pull-left" title="Количество комментариев" data-toggle="tooltip" data-placement="bottom">
                    <i class="material-icons">chat_bubble</i>
                    <a href="#comments">
                    <span class="count-comments" itemprop="commentCount">
                        {{ count($page->publishedComments) }}
                    </span>
                    </a>
                </div>
                <!-- Сохранение страницы в сохраненное -->
                @include('widgets.savedPages')
            </div>
        </div>

        {{ $areaWidget->contentTop() }}

        <div class="content" itemprop="articleBody">
            @if($page->image)
                <a class="fancybox pull-left" rel="group-content" href="{{ $page->getImageLink('origin') }}">
                    {{ $page->getImage('origin', ['class' => 'page-image']) }}
                </a>
            @endif
            {{ $page->getContentWithWidget() }}

            @if(count($page->tags))
                <div class="clearfix"></div>
                <ul class="tags">
                    @foreach($page->tags as $tag)
                        <li>
                            <a href="{{ URL::route('journal.tag', ['journalAlias' => Config::get('settings.journalAlias'), 'tag' => $tag->title]) }}" class="tag btn btn-sm btn-primary">
                                {{ $tag->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif

            <div class="clearfix"></div>
            @include('widgets.sidebar.socialButtons')
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
