<?php $menuWidget = app('MenuWidget') ?>
<?php $sidebarWidget = app('SidebarWidget') ?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $page->meta_title }}</title>

    <meta name="description" content="{{ $page->meta_desc }}"/>
    <meta name="keywords" content="{{ $page->meta_key }}"/>
    <meta name="copyright" lang="ru" content="{{ Config::get('settings.metaCopyright') }}" />
    <meta name="author" content="{{ Config::get('settings.metaAuthor') }}" />
    <meta name="robots" content="{{ Config::get('settings.metaRobots') }}"/>

    <link rel="icon" href="{{ URL::to('favicon.ico') }}">

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

<header class="container">
    <div class="row">
        <div class="col-xs-12">
            {{ $menuWidget->topMenu() }}
            {{ Auth::user()->login }}
            Шапка
        </div>
    </div>
</header>

{{ $menuWidget->mainMenu() }}

<div class="container">
    <div class="row">
        <div class="col-lg-3 col-md-3">

            {{ $sidebarWidget->popular() }}

            {{ $sidebarWidget->best() }}

        </div>

        <div class="col-lg-6 col-md-6">
            Количество просмотров: {{ $page->views }}
            @yield('content')
        </div>

        <div class="col-lg-3 col-md-3">

            {{ $sidebarWidget->comments() }}

            {{ $sidebarWidget->latest() }}

            {{ $sidebarWidget->unpopular() }}

        </div>
    </div>
</div>

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