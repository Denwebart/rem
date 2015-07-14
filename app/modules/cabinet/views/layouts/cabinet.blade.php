<?php $menuWidget = app('MenuWidget') ?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    <meta name="description" content=""/>
    <meta name="keywords" content=""/>
    <meta name="copyright" lang="ru" content="{{ Config::get('settings.metaCopyright') }}" />
    <meta name="author" content="{{ Config::get('settings.metaAuthor') }}" />
    <meta name="robots" content="{{ Config::get('settings.metaRobots') }}"/>

    <link rel="icon" href="{{ URL::to('favicon.ico') }}">

    <!-- material -->
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,300italic,400italic,500,500italic,100,100italic,700,700italic&subset=latin,cyrillic,cyrillic-ext' rel='stylesheet' type='text/css'>
    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('css/style.css') }}
    {{ HTML::style('css/cabinet.css') }}
    <link href="/material/css/roboto.min.css" rel="stylesheet">
    <link href="/material/css/material-fullpalette.min.css" rel="stylesheet">
    <link href="/material/css/ripples.min.css" rel="stylesheet">
    <link href="/material/css/snackbar.min.css" rel="stylesheet">

    <style>
        body{padding-top:50px}#banner{border-bottom:none}.page-header h1{font-size:4em}.bs-docs-section{margin-top:8em}.bs-component{position:relative}.bs-component .modal{position:relative;top:auto;right:auto;left:auto;bottom:auto;z-index:1;display:block}.bs-component .modal-dialog{width:90%}.bs-component .popover{position:relative;display:inline-block;width:220px;margin:20px}#source-button{position:absolute;top:0;right:0;z-index:100;font-weight:bold;padding: 5px 10px;}.progress{margin-bottom:10px}footer{margin:5em 0}footer li{float:left;margin-right:1.5em;margin-bottom:1.5em}footer p{clear:left;margin-bottom:0}.splash{padding:4em 0 0;background-color:#141d27;color:#fff;text-align:center}.splash h1{font-size:4em}.splash #social{margin:2em 0}.splash .alert{margin:2em 0}.section-tout{padding:4em 0 3em;border-bottom:1px solid rgba(0,0,0,0.05);background-color:#eaf1f1}.section-tout .fa{margin-right:.5em}.section-tout p{margin-bottom:3em}.section-preview{padding:4em 0 4em}.section-preview .preview{margin-bottom:4em;background-color:#eaf1f1}.section-preview .preview .image{position:relative}.section-preview .preview .image:before{box-shadow:inset 0 0 0 1px rgba(0,0,0,0.1);position:absolute;top:0;left:0;width:100%;height:100%;content:"";pointer-events:none}.section-preview .preview .options{padding:1em 2em 2em;border:1px solid rgba(0,0,0,0.05);border-top:none;text-align:center}.section-preview .preview .options p{margin-bottom:2em}.section-preview .dropdown-menu{text-align:left}.section-preview .lead{margin-bottom:2em}@media (max-width:767px){.section-preview .image img{width:100%}}.sponsor{text-align:center}.sponsor a:hover{text-decoration:none}@media (max-width:767px){#banner{margin-bottom:2em;text-align:center}}
        .infobox .btn-sup { color: rgba(0,0,0,0.5); font-weight: bold; font-size: 15px; line-height: 30px; }
        .infobox .btn-sup img { opacity: 0.5; height: 30px;}
        .infobox .btn-sup span { padding-left: 10px; position: relative; top: 2px; }
        .icons-material .row { margin-bottom: 20px; }
        .icons-material .col-xs-2 { text-align: center; }
        .icons-material i { font-size: 34pt; }

        .icon-preview {
            display: inline-block;
            padding: 10px;
            margin: 10px;
            background: #D5D5D5;
            border-radius: 3px;
            cursor: pointer;
        }
        .icon-preview span {
            display: none;
            position: absolute;
            background: black;
            color: #EEE;
            padding: 5px 8px;
            font-size: 15px;
            font-family: Roboto;
            border-radius: 2px;
            z-index: 10;
        }
        .icon-preview:hover i { color: #4285f4; }
        .icon-preview:hover span { display: block; cursor: text; }

    </style>

    {{HTML::script('js/jquery-1.10.2.min.js')}}
    {{HTML::script('js/bootstrap.min.js')}}

    @yield('style')

</head>
<body class="{{ (Auth::check()) ? 'margin-top-50' : ''}}">

@if(Auth::check())
    {{ $headerWidget->show() }}
@endif

<!-- Header -->
@include('header')

{{ $menuWidget->mainMenu() }}

<div class="container">
    <div class="row">

        <div class="col-lg-11 col-md-6">
            @if(Session::has('rulesSuccessMessage'))
                <div class="alert alert-dismissable alert-success">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    {{ Session::get('rulesSuccessMessage') }}
                </div>
            @endif

            @yield('content')
        </div>

        <div class="col-lg-1 col-md-3" id="users-menu">
            <ul>
                <li class="{{ Route::is('user.profile') ? 'active' : '' }}">
                    <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                        <span class="glyphicon glyphicon-user"></span>
                        <span>{{ Auth::check() ? (Auth::user()->is($user) ? 'Мой профиль' : 'Профиль') : 'Профиль' }}</span>
                    </a>
                </li>
                <li class="{{ Route::is('user.gallery') ? 'active' : '' }}">
                    <a href="{{ URL::route('user.gallery', ['login' => $user->getLoginForUrl()]) }}">
                        <span class="fa fa-car"></span>
                        <span>{{ Auth::check() ? (Auth::user()->is($user) ? 'Мой автомобиль' : 'Aвтомобиль') : 'Автомобиль' }}</span>
                    </a>
                </li>
                <li class="{{ Route::is('user.questions') ? 'active' : '' }}">
                    <a href="{{ URL::route('user.questions', ['login' => $user->getLoginForUrl()]) }}">
                        <span class="glyphicon glyphicon-question-sign"></span>
                        <span>{{ Auth::check() ? (Auth::user()->is($user) ? 'Мои вопросы' : 'Вопросы') : 'Вопросы' }}</span>
                    </a>
                </li>
                <li class="{{ Route::is('user.journal') ? 'active' : '' }}">
                    <a href="{{ URL::route('user.journal', ['journalAlias' => Config::get('settings.journalAlias'), 'login' => $user->getLoginForUrl()]) }}">
                        <span class="glyphicon glyphicon-book"></span>
                        <span>{{ Auth::check() ? (Auth::user()->is($user) ? 'Мой журнал' : 'Бортовой журнал') : 'Бортовой журнал' }}</span>
                    </a>
                </li>
                <li class="{{ Route::is('user.comments') ? 'active' : '' }}">
                    <a href="{{ URL::route('user.comments', ['login' => $user->getLoginForUrl()]) }}">
                        <span class="icon mdi-communication-messenger"></span>
                        <span>{{ Auth::check() ? (Auth::user()->is($user) ? 'Мои комментарии' : 'Комментарии') : 'Комментарии' }}</span>
                    </a>
                </li>
                <li class="{{ Route::is('user.answers') ? 'active' : '' }}">
                    <a href="{{ URL::route('user.answers', ['login' => $user->getLoginForUrl()]) }}">
                        <span class="icon mdi-communication-forum"></span>
                        <span>{{ Auth::check() ? (Auth::user()->is($user) ? 'Мои ответы' : 'Ответы') : 'Ответы' }}</span>
                    </a>
                </li>
                @if(Auth::check())
                    @if(Auth::user()->is($user) || Auth::user()->isAdmin())
                        <li class="messages {{ Route::is('user.messages') ? 'active' : '' }}">
                            <a href="{{ URL::route('user.messages', ['login' => $user->getLoginForUrl()]) }}">
                                <span class="glyphicon glyphicon-send"></span>
                                <span>Личные сообщения</span>
                                @if(Auth::user()->is($user))
                                    @if($newMessages = count($headerWidget->newMessages))
                                        <small class="label label-info">{{ $newMessages }}</small>
                                    @endif
                                @endif
                            </a>
                        </li>
                        <li class="messages {{ Route::is('user.savedPages') ? 'active' : '' }}">
                            <a href="{{ URL::route('user.savedPages', ['login' => $user->getLoginForUrl()]) }}">
                                <span class="glyphicon glyphicon-floppy-disk"></span>
                                <span>Сохранённое</span>
                            </a>
                        </li>
                    @endif
                    <li class="{{ Route::is('user.subscriptions') ? 'active' : '' }}">
                        <a href="{{ URL::route('user.subscriptions', ['login' => $user->getLoginForUrl()]) }}">
                            <span class="glyphicon glyphicon-heart-empty"></span>
                            <span>{{ Auth::user()->is($user) ? 'Мои подписки' : 'Подписки'}}</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            {{ $areaWidget->siteBottom() }}
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

<!-- JS -->
<script>
    (function(){

        var $button = $("<div id='source-button' class='btn btn-primary btn-xs'>&lt; &gt;</div>").click(function(){
            var index =  $('.bs-component').index( $(this).parent() );
            $.get(window.location.href, function(data){
                var html = $(data).find('.bs-component').eq(index).html();
                html = cleanSource(html);
                $("#source-modal pre").text(html);
                $("#source-modal").modal();
            })

        });

        $('.bs-component [data-toggle="popover"]').popover();
        $('.bs-component [data-toggle="tooltip"]').tooltip();

        $(".bs-component").hover(function(){
            $(this).append($button);
            $button.show();
        }, function(){
            $button.hide();
        });

        function cleanSource(html) {
            var lines = html.split(/\n/);

            lines.shift();
            lines.splice(-1, 1);

            var indentSize = lines[0].length - lines[0].trim().length,
                    re = new RegExp(" {" + indentSize + "}");

            lines = lines.map(function(line){
                if (line.match(re)) {
                    line = line.substring(indentSize);
                }

                return line;
            });

            lines = lines.join("\n");

            return lines;
        }

        $(".icons-material .icon").each(function() {
            $(this).after("<br><br><code>" + $(this).attr("class").replace("icon ", "") + "</code>");
        });

    })();

</script>
<script src="/material/js/ripples.min.js"></script>
<script src="/material/js/material.min.js"></script>
<script src="/material/js/snackbar.min.js"></script>

<script src="/material/js/jquery.nouislider.min.js"></script>
<script>
    $(function() {
        $.material.init();
    });
</script>

@yield('script')

</body>
</html>