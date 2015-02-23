<?php $headerWidget = app('HeaderWidget') ?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title></title>

    <meta name="description" content=""/>
    <meta name="keywords" content=""/>
    <meta name="copyright" lang="ru" content="{{ Config::get('settings.metaCopyright') }}" />
    <meta name="author" content="{{ Config::get('settings.metaAuthor') }}" />
    <meta name="robots" content="{{ Config::get('settings.metaRobots') }}"/>

    <link rel="icon" href="{{ URL::to('favicon.ico') }}">

    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('css/style.css') }}
    {{ HTML::style('css/cabinet.css') }}
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
                <li>
                    <a href="{{ URL::route('user.profile', ['login' => $user->login]) }}">
                        <span class="glyphicon glyphicon-user"></span>
                        <span>Мой профиль</span>
                    </a>
                </li>
                <li>
                    <a href="{{ URL::route('user.gallery', ['login' => $user->login]) }}">
                        <span class="glyphicon glyphicon-picture"></span>
                        <span>Мои фотографии</span>
                    </a>
                </li>
                <li>
                    <a href="{{ URL::route('user.questions', ['login' => $user->login]) }}">
                        <span class="glyphicon glyphicon-question-sign"></span>
                        <span>Мои вопросы</span>
                    </a>
                </li>
                <li>
                    <a href="{{ URL::route('user.comments', ['login' => $user->login]) }}">
                        <span class="glyphicon glyphicon-comment"></span>
                        <span>Мои комментарии</span>
                    </a>
                </li>
                <li>
                    <a href="{{ URL::route('user.messages', ['login' => $user->login]) }}">
                        <span class="glyphicon glyphicon-send"></span>
                        <span>Личные сообщения</span>
                    </a>
                </li>
                <li>
                    <a href="{{ URL::route('user.friends', ['login' => $user->login]) }}">
                        <span class="glyphicon glyphicon-heart-empty"></span>
                        <span>Мои друзья</span>
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

</body>
</html>