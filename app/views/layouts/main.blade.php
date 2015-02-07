<?php $menuWidget = app('MenuWidget') ?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Новый шаблон</title>
    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('css/style.css') }}
</head>
<body>

@if (!Auth::check())
    {{--<form class="navbar-form navbar-right" role="form" action="{{ action('UsersController@postLogin') }}" method="post">--}}
        {{--<a href="/users/login" class="btn btn-success">Войти</a>--}}
        {{--<a href="/users/register" class="btn btn-success">Регистрация</a>--}}
    {{--</form>--}}
@else
    {{--<form class="navbar-form navbar-right" role="form" action="/users/logout">--}}
        {{--<button class="btn btn-success">Выйти</button>--}}
    {{--</form>--}}
    {{--<ul class="nav navbar-nav navbar-right">--}}
        {{--<li><a href="#"><strong>{{ Auth::user()->username }}</strong></a></li>--}}
    {{--</ul>--}}
@endif

{{ $menuWidget->topMenu() }}

<header class="container">
    <div class="row">
        <div class="col-xs-12">
            Шапка
        </div>
    </div>
</header>

{{ $menuWidget->mainMenu() }}

@yield('content')

<footer class="container">
    <div class="row">
        <div class="col-xs-12" style="background: gray">
            {{ $menuWidget->bottomMenu() }}
            Футер
        </div>
    </div>
</footer>

{{HTML::script('js/jquery-1.11.2.min.js')}}
{{HTML::script('js/bootstrap.min.js')}}
</body>
</html>