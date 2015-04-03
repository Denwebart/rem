<?php $menuWidget = app('MenuWidget') ?>
<?php $sidebarWidget = app('SidebarWidget') ?>
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
    <link rel="stylesheet" href="/backend/css/font-awesome.min.css" />
</head>
<body class="{{ (Auth::check()) ? 'margin-top-50' : ''}}">

@if(Auth::check())
    {{ $headerWidget->show() }}
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
                <!--Search-->
                <div id="search">
                    {{ Form::open(['method' => 'GET', 'route' => ['search']], ['id' => 'search-form']) }}

                    <div class="col-md-10">
                        <div class="form-group">
                            {{ Form::input('search', 'query', $query, ['class' => 'form-control', 'id' => 'name']) }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        {{ Form::submit('Найти', ['class' => 'btn btn-success']) }}
                    </div>

                    {{ Form::close() }}
                </div>
            </div>
            <div class="col-md-2">
                {{ $menuWidget->topMenu() }}
                @if (!Auth::check())
                    <a href="{{ URL::to('users/login') }}" class="btn btn-primary margin-top-20 pull-right">
                        Войти
                    </a>
                    <a href="{{ URL::to('users/register') }}" class="btn btn-primary margin-top-10 pull-right">
                        Зарегистрироваться
                    </a>
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

{{ $menuWidget->mainMenu() }}

<div class="container">
    <div class="row">

        <div class="col-lg-8 col-md-8">
            @yield('content')
        </div>

        <div class="col-lg-4 col-md-4">

            Правая колонка

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