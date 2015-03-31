<?php $menuWidget = app('MenuWidget') ?>
<?php $sidebarWidget = app('SidebarWidget') ?>
<?php $headerWidget = app('HeaderWidget') ?>
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
    <link rel="stylesheet" href="/backend/css/font-awesome.min.css" />
</head>
<body class="{{ (Auth::check()) ? 'margin-top-50' : ''}}">

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

@if(Auth::check())
    {{ $headerWidget->show($page) }}
@endif

<div class="header">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div id="logo">
                    <a href="{{ URL::to('/') }}">
                        {{ HTML::image('images/logo.png') }}
                    </a>
                </div>
            </div>
            <div class="col-md-5">
                <div id="site-title">
                    <h1>
                        Школа авторемонта
                        <span class="slogan">
                            Статьи, советы и рекомендации по ремонту и обслуживанию автомобилей своими руками
                        </span>
                    </h1>
                </div>
            </div>
            <div class="col-md-2">
                {{ $menuWidget->topMenu() }}
                @if (!Auth::check())
                    <a href="{{ URL::to('users/login') }}" class="btn btn-primary margin-top-50 pull-right">Войти</a>
                @endif
            </div>
        </div>
    </div>
</div>

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

            {{ $sidebarWidget->addToFavorites() }}

            {{ $sidebarWidget->comments() }}

            {{ $sidebarWidget->latest() }}

            {{ $sidebarWidget->unpopular() }}

        </div>
    </div>
</div>

<footer class="container">
    <div class="row">
        <div class="col-xs-12">
            {{ $menuWidget->bottomMenu() }}
        </div>
    </div>
</footer>

{{HTML::script('js/jquery-1.11.2.min.js')}}
{{HTML::script('js/bootstrap.min.js')}}

@yield('script')

</body>
</html>