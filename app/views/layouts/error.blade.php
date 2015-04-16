<?php $menuWidget = app('MenuWidget') ?>
<?php $headerWidget = app('HeaderWidget') ?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Страница не найдена</title>

    <meta name="copyright" lang="ru" content="{{ Config::get('settings.metaCopyright') }}" />
    <meta name="author" content="{{ Config::get('settings.metaAuthor') }}" />
    <meta name="robots" content="noindex, nofollow"/>

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
                            {{ Form::input('search', 'query', null, ['class' => 'form-control', 'id' => 'query']) }}
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
                    <a href="{{ URL::to('users/login') }}" class="btn btn-primary margin-top-50 pull-right">Войти</a>
                @endif
            </div>
        </div>
    </div>
</div>

{{ $menuWidget->mainMenu() }}

<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            @yield('content')
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