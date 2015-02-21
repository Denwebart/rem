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
</head>
<body id="cabinet">

<header class="container">
    <div class="row">
        <div class="col-xs-12">
            {{ Auth::user()->login }}
            Шапка
        </div>
    </div>
</header>

<div class="container">
    <div class="row">

        <div class="col-lg-11 col-md-6">
            @yield('content')
        </div>

        <div class="col-lg-1 col-md-3" style="background: #728AAC">

            <a href="">

            </a>

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
</body>
</html>