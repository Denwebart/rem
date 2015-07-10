@extends('cabinet::layouts.cabinet')

<?php
$title = Auth::check() ? (Auth::user()->is($user) ? 'Мой профиль' : 'Профиль пользователя ' . $user->login) : 'Профиль пользователя ' . $user->login;
View::share('title', $title);
?>

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('/') }}">Главная</a></li>
                <li>{{ $title }}</li>
            </ol>
        </div>

        <div class="col-lg-3">
            <div class="avatar">
                {{ $user->getAvatar() }}
            </div>
            @if(Auth::check())
                @if(!Auth::user()->is($user))
                    <a href="{{ URL::route('user.dialog', ['login' => Auth::user()->getLoginForUrl(), 'companion' => $user->getLoginForUrl()]) }}" class="btn btn-primary">
                        Написать личное сообщение
                    </a>
                @endif
                @if(Auth::user()->isAdmin())
                    @include('widgets.ban', ['user' => $user])
                @endif
            @endif

            {{ $areaWidget->leftSidebar() }}
        </div>
        <div class="col-lg-9">

            @if(Auth::check())
                @if(Auth::user()->is($user) && !Auth::user()->is_agree)
                    @include('messages.rulesAgree')
                @endif

                @if(Auth::user()->is($user) && Auth::user()->is_banned)
                    @include('cabinet::user.banMessage')
                @endif

                @if(Auth::user()->is($user) && Ip::isBanned())
                    @include('messages.bannedIp')
                @endif
            @endif

            @if(Auth::check())
                @if((Auth::user()->is($user) && !IP::isBanned() && !$user->is_banned) || Auth::user()->isAdmin())
                    <a href="{{{ URL::route('user.edit', ['login' => $user->getLoginForUrl()]) }}}" class="pull-right">
                        Редактировать
                        <span class="mdi-editor-border-color"></span>
                    </a>
                @endif
            @endif

            @if(Auth::check())
                @if(Auth::user()->is($user))
                    <div class="clearfix"></div>
                    <a href="{{{ URL::route('user.changePassword', ['login' => $user->getLoginForUrl()]) }}}" class="pull-right">
                        Изменить пароль
                        <!-- mdi-communication-vpn-key -->
                        <!-- mdi-action-verified-user -->
                        <span class="mdi-hardware-security"></span>
                    </a>
                @endif
            @endif

            @if(Session::has('successMessage'))
                <div class="alert alert-dismissable alert-info">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    {{ Session::get('successMessage') }}
                </div>
            @endif

            <h2>{{{ $user->login }}}</h2>

            @if(Auth::check())
                @if(Auth::user()->is($user) || Auth::user()->isAdmin() || Auth::user()->isModerator())
                    <p class="email">{{{ $user->email }}}</p>
                @endif
            @endif

            @if($user->isAdmin() || $user->isModerator())
                <p>{{ User::$roles[$user->role] }}</p>
            @endif

            @if($user->getFullName())
                <h3>{{{ $user->getFullName() }}}</h3>
            @endif

            @if($user->country)
                <p>{{{ $user->country }}}</p>
            @endif

            @if($user->city)
                <p>{{{ $user->city }}}</p>
            @endif

            @if($user->car_brand)
                <p>{{{ $user->car_brand }}}</p>
            @endif

            @if($user->profession)
                <p>{{{ $user->profession }}}</p>
            @endif

            @if($user->profession)
                <p>{{ $user->description }}</p>
            @endif

            <h2>Награды</h2>

            @if(count($user->honors))
                @foreach($user->honors as $honor)
                    <a href="{{ URL::route('honor.info', ['alias' => $honor->alias]) }}">
                        {{ $honor->getImage(null, ['width' => '75px']) }}
                    </a>
                @endforeach
            @else
                @if(Auth::check())
                    @if(!Auth::user()->is($user))
                        Нет наград.
                    @else
                        У Вас нет наград. Узнать о том, как можно получить награду, можно
                        <a href="">здесь</a>.
                    @endif
                @else
                    Нет наград.
                @endif
            @endif

            {{ $areaWidget->contentBottom() }}

        </div>
    </div>
@stop