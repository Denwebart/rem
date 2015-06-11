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
            @endif
        </div>
        <div class="col-lg-9">

            @if(Auth::check())
                @if(Auth::user()->is($user) && !Auth::user()->is_agree)
                    <div class="alert alert-dismissable alert-warning">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <h4>Вы не согласились с правилами сайта!</h4>
                        <p>Пока вы не соглавитесь с правилами сайта, для вас будут
                            недоступны такие разделы личного кабинета, как "Редактирование профиля", "Мой автомобиль",
                            "Мои вопросы", "Мой журнал", "Мои комментарии", "Личные сообщения".
                            Для ознакомления с правилами перейдите по <a href="{{ URL::route('rules') }}" class="alert-link">ссылке</a>.</p>
                    </div>
                @endif
            @endif

            @if(Auth::check())
                @if(Auth::user()->is($user) || Auth::user()->isAdmin())
                    <a href="{{{ URL::route('user.edit', ['login' => $user->getLoginForUrl()]) }}}" class="pull-right">
                        <span class="glyphicon glyphicon-edit"></span>
                        Редактировать
                    </a>
                @endif
            @endif

            <h2>{{{ $user->login }}}</h2>
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

        </div>
    </div>
@stop