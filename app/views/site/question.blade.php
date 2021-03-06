@extends('layouts.main')

@section('breadcrumbs')
    <!-- Breadcrumbs -->
    @include('widgets.breadcrumbs', ['items' => [
        [
            'title' => $page->parent->parent->getTitle(),
            'url' => URL::to($page->parent->parent->getUrl())
        ],
        [
            'title' => Str::limit($page->parent->getTitle(), 50),
            'url' => URL::to($page->parent->getUrl())
        ],
        [
            'title' => $page->getTitleForBreadcrumbs()
        ]
    ]])
@stop

@section('content')
    <section id="content" class="well" itemscope itemtype="http://schema.org/Question">

        <div class="row">
            <div class="@if($page->showRating()) col-lg-9 col-md-12 col-sm-9 col-xs-12 @else col-lg-12 col-md-12 col-sm-12 col-xs-12 @endif">
                <h2 itemprop="name">
                    {{ $page->title }}
                    @if(count($page->bestComments))
                        <i class="material-icons mdi-success" title="Есть решение" data-toggle="tooltip" data-placement="bottom" style="font-size: 26px">done</i>
                    @endif

                    @if(Auth::check())
                        @if(Auth::user()->isAdmin() || Auth::user()->isModerator())
                            <a href="{{ URL::route('admin.questions.edit', ['id' => $page->id, 'backUrl' => urlencode(Request::url())]) }}" class="margin-left-10" title="Редактировать вопрос">
                                <i class="material-icons">mode_edit</i>
                            </a>
                        @elseif((Auth::user()->is($page->user) && !Ip::isBanned() && !Auth::user()->is_banned && $page->isEditable()))
                            <a href="{{ URL::route('user.questions.edit', ['login' => $page->user->getLoginForUrl(),'id' => $page->id, 'backUrl' => urlencode(Request::url())]) }}" class="margin-left-10" title="Редактировать вопрос">
                                <i class="material-icons">mode_edit</i>
                            </a>
                        @endif
                    @endif
                </h2>
            </div>
            @if($page->showRating())
                <div class="col-lg-3 col-md-12 col-sm-3 col-xs-12">
                    {{-- Рейтинг --}}
                    @include('widgets.rating')

                    <div class="date pull-left hidden-lg hidden-md hidden-sm">
                        <i class="material-icons pull-left">today</i>
                        <span class="pull-left">{{ DateHelper::dateFormat($page->published_at) }}</span>
                    </div>
                </div>
            @endif
        </div>

        <div class="page-info">
            <div class="pull-left">
                <div class="user pull-left" itemprop="author" itemscope itemtype="http://schema.org/Person">
                    <a href="{{ URL::route('user.profile', ['login' => $page->user->getLoginForUrl()]) }}" itemprop="url">
                        {{ $page->user->getAvatar('mini', ['width' => '25', 'class' => 'pull-left']) }}
                        <span class="login pull-left hidden-xs hidden-md" itemprop="name">{{ $page->user->login }}</span>
                    </a>
                </div>
                <div class="date pull-left hidden-xs">
                    <i class="material-icons hidden-md">today</i>
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

                <div class="answers-count pull-left" title="Количество ответов" data-toggle="tooltip" data-placement="bottom">
                    <i class="material-icons">question_answer</i>
                    <a href="#answers">
                        <span class="count-comments" itemprop="answerCount">
                            {{ count($page->publishedAnswers) }}
                        </span>
                    </a>
                </div>

                <div class="subscribers pull-left" title="Количество подписавшихся на вопрос" data-toggle="tooltip" data-placement="bottom">
                    <i class="material-icons">local_library</i>
                    <span>{{ count($page->subscribers) }}</span>
                </div>

                <!-- Сохранение страницы в сохраненное -->
                @include('widgets.savedPages')

            </div>
        </div>

        {{ $areaWidget->contentTop() }}

        <div class="content" itemprop="text">
            @if($page->image)
                <a class="fancybox pull-left" data-fancybox-group="group-content" href="{{ $page->getImageLink('origin') }}">
                    {{ $page->getImage('origin', ['class' => 'page-image']) }}
                </a>
            @else
                <meta itemprop="image" content="{{ URL::to(Config::get('settings.defaultImage')) }}">
            @endif
            {{ $page->getContentWithWidget() }}
        </div>

        <div class="clearfix"></div>
        @include('widgets.sidebar.socialButtons')

        <!-- Подписка на вопрос ("Подписки") -->
        <div class="clearfix"></div>
        @include('widgets.subscribe', ['subscriptionObject' => $page, 'subscriptionField' => Subscription::FIELD_PAGE_ID])

        {{ $areaWidget->contentMiddle() }}

        <div id="answers">
            {{-- Комментарии --}}
            <?php
                $commentWidget = app('CommentWidget');
                $commentWidget->title = 'Ответы';
                $commentWidget->formTitle = 'Написать ответ';
                $commentWidget->successMessage = 'Спасибо за ответ!';
            ?>
            {{ $commentWidget->show($page) }}
        </div>

        {{-- Читайте также --}}
        <?php $relatedWidget = app('RelatedWidget') ?>
        {{ $relatedWidget->questions($page) }}
        {{ $relatedWidget->articles($page) }}

        {{ $areaWidget->contentBottom() }}

    </section>
@stop
