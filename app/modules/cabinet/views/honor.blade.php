@extends('cabinet::layouts.honors')

<?php
$title = $honor->title;
View::share('title', $title);
?>

@section('content')
    <ol class="breadcrumb">
        <li class="home-page">
            <a href="{{ URL::to('/') }}">
                <i class="material-icons">home</i>
            </a>
        </li>
        <li><a href="{{ URL::route('honors') }}">Награды</a></li>
        <li class="hidden-md hidden-xs">{{ $honor->title }}</li>
    </ol>

    <section id="content">

        <div class="row">
            <div class="col-md-7">
                <div class="well">
                    <div id="honor-info">
                        <h2>{{ $honor->title }}</h2>

                        <div class="honor-image">
                            {{ $honor->getImage() }}
                        </div>

                        @if($honor->description)
                            <div class="honor-description">
                                {{ $honor->description }}
                            </div>
                        @endif
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
                                <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}" class="avatar-link gray-background display-inline-block">
                                    {{ $user->getAvatar('mini', ['class' => 'avatar circle', 'data-placement' => 'right']) }}
                                    @if($user->isOnline())
                                        <span class="is-online-status online" title="Сейчас на сайте" data-toggle="tooltip" data-placement="right"></span>
                                    @else
                                        <span class="is-online-status offline" title="Последний раз был {{ DateHelper::getRelativeTime($user->last_activity) }}" data-toggle="tooltip" data-placement="right"></span>
                                    @endif
                                </a>
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
