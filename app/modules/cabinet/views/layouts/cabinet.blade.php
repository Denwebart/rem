<?php $headerWidget = app('HeaderWidget') ?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title }}</title>

    <meta name="description" content=""/>
    <meta name="keywords" content=""/>
    <meta name="copyright" lang="ru" content="{{ Config::get('settings.metaCopyright') }}" />
    <meta name="author" content="{{ Config::get('settings.metaAuthor') }}" />
    <meta name="robots" content="{{ Config::get('settings.metaRobots') }}"/>

    <link rel="icon" href="{{ URL::to('favicon.ico') }}">

    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('css/style.css') }}
    {{ HTML::style('css/cabinet.css') }}
    <link rel="stylesheet" href="/backend/css/font-awesome.min.css" />
</head>
<body id="cabinet" class="fixed{{ (Auth::check()) ? ' margin-top-50' : ''}}">

{{ $headerWidget->show() }}

<div class="container">
    <div class="row">

        <div class="col-lg-11 col-md-6">
            @yield('content')
        </div>

        <div class="col-lg-1 col-md-3" id="users-menu">
            <ul>
                <li class="{{ Route::is('user.profile') ? 'active' : '' }}">
                    <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                        <span class="glyphicon glyphicon-user"></span>
                        <span>{{ (Auth::user()->is($user)) ? 'Мой профиль' : 'Профиль' }}</span>
                    </a>
                </li>
                <li class="{{ Route::is('user.gallery') ? 'active' : '' }}">
                    <a href="{{ URL::route('user.gallery', ['login' => $user->getLoginForUrl()]) }}">
                        <span class="fa fa-car"></span>
                        <span>{{ (Auth::user()->is($user)) ? 'Мой автомобиль' : 'Aвтомобиль' }}</span>
                    </a>
                </li>
                <li class="{{ Route::is('user.questions') ? 'active' : '' }}">
                    <a href="{{ URL::route('user.questions', ['login' => $user->getLoginForUrl()]) }}">
                        <span class="glyphicon glyphicon-question-sign"></span>
                        <span>{{ (Auth::user()->is($user)) ? 'Мои вопросы' : 'Вопросы' }}</span>
                    </a>
                </li>
                <li class="{{ Route::is('user.articles') ? 'active' : '' }}">
                    <a href="{{ URL::route('user.articles', ['login' => $user->getLoginForUrl()]) }}">
                        <span class="glyphicon glyphicon-question-sign"></span>
                        <span>{{ (Auth::user()->is($user)) ? 'Мои статьи' : 'Статьи' }}</span>
                    </a>
                </li>
                <li class="{{ Route::is('user.comments') ? 'active' : '' }}">
                    <a href="{{ URL::route('user.comments', ['login' => $user->getLoginForUrl()]) }}">
                        <span class="fa fa-comment"></span>
                        <span>{{ (Auth::user()->is($user)) ? 'Мои комментарии' : 'Комментарии' }}</span>
                    </a>
                </li>
                @if(Auth::user()->is($user) || Auth::user()->isAdmin())
                    <li class="messages {{ Route::is('user.messages') ? 'active' : '' }}">
                        <a href="{{ URL::route('user.messages', ['login' => $user->getLoginForUrl()]) }}">
                            <span class="glyphicon glyphicon-send"></span>
                            <span>Личные сообщения</span>
                            @if(count($headerWidget->newMessages()) && Auth::user()->is($user))
                                <small class="label label-info">{{ count($headerWidget->newMessages()) }}</small>
                            @endif
                        </a>
                    </li>
                @endif
                <li class="{{ Route::is('user.subscriptions') ? 'active' : '' }}">
                    <a href="{{ URL::route('user.subscriptions', ['login' => $user->getLoginForUrl()]) }}">
                        <span class="glyphicon glyphicon-heart-empty"></span>
                        <span>{{ (Auth::user()->is($user)) ? 'Мои подписки' : 'Подписки' }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<footer class="container">
    <div class="row">
        <div class="col-xs-12">
        </div>
    </div>
</footer>

{{HTML::script('js/jquery-1.11.2.min.js')}}
{{HTML::script('js/bootstrap.min.js')}}
{{HTML::script('js/custom.js')}}

@yield('script')

</body>
</html>