@extends('cabinet::layouts.honors')

<?php
$title = $honor->title;
View::share('title', $title);
?>

@section('content')
    <!-- Breadcrumbs -->
    @include('widgets.breadcrumbs', ['items' => [
        [
            'title' => 'Награды',
            'url' => URL::route('honors')
        ],
        [
            'title' => $honor->title
        ]
    ]])

    <section id="content">

        <div class="row">
            <div class="col-md-7">
                <div class="well">
                    <div id="honor-info" itemscope itemtype="http://schema.org/Article">

                        <meta itemprop="datePublished" content="{{ DateHelper::dateFormatForSchema(\Carbon\Carbon::now()) }}">
                        <h2 itemprop="headline">{{ $honor->title }}</h2>

                        <div itemprop="articleBody">
                            <div class="honor-image">
                                {{ $honor->getImage(null, [], true) }}
                            </div>

                            @if($honor->description)
                                <div class="honor-description">
                                    {{ $honor->description }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <h3 style="margin-top: 0px; font-weight: 300;">
                    Награжденные пользователи
                </h3>

                @if(count($honor->users))
                    <div id="rewarded-users">
                        @foreach($honor->users as $user)
                            <div class="user">
                                <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}" class="avatar-link gray-background display-inline-block pull-left">
                                    {{ $user->getAvatar('mini', ['class' => 'avatar circle', 'data-placement' => 'right']) }}
                                    @if($user->isOnline())
                                        <span class="is-online-status online" title="Сейчас на сайте" data-toggle="tooltip" data-placement="right"></span>
                                    @else
                                        <span class="is-online-status offline" title="Последний раз был {{ DateHelper::getRelativeTime($user->last_activity) }}" data-toggle="tooltip" data-placement="right"></span>
                                    @endif
                                </a>
                                @if($user->awardsNumber > 1)
                                    <span class="count pull-left">
                                        x {{ $user->awardsNumber }}
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>Еще ни у кого нет этой награды.</p>
                @endif
            </div>
        </div>

        {{ $areaWidget->contentBottom() }}

    </section>
@stop
