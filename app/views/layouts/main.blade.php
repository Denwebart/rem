<?php $menuWidget = app('MenuWidget') ?>
<?php $sidebarWidget = app('SidebarWidget') ?>
<?php
if(Auth::check()){
    $headerWidget = app('HeaderWidget');
}
?>
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

    <!-- material -->
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,300italic,400italic,500,500italic,100,100italic,700,700italic&subset=latin,cyrillic,cyrillic-ext' rel='stylesheet' type='text/css'>
    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('css/style.css') }}
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

    @yield('style')
</head>

<body class="{{ (Auth::check()) ? 'margin-top-50' : ''}}">

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

            {{ $sidebarWidget->rss() }}

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

<!-- JS -->
{{HTML::script('js/jquery-1.10.2.min.js')}}
{{HTML::script('js/bootstrap.min.js')}}

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
        $(".shor").noUiSlider({
            start: 40,
            connect: "lower",
            range: {
                min: 0,
                max: 100
            }
        });

        $(".svert").noUiSlider({
            orientation: "vertical",
            start: 40,
            connect: "lower",
            range: {
                min: 0,
                max: 100
            }
        });
    });
</script>

@yield('script')

</body>
</html>