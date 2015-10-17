<?php $menuWidget = app('MenuWidget') ?>
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
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="57x57" href="/images/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/images/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/images/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/images/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/images/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/images/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/images/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/images/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/images/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/images/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="/images/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/images/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <!-- material -->
    {{ HTML::style('css/bootstrap.min.css') }}
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,300italic,400italic,500,500italic,100,100italic,700,700italic&subset=latin,cyrillic,cyrillic-ext' rel='stylesheet' type='text/css'>
    <link href="/material/css/material.min.css" rel="stylesheet">
    <link href="/material/css/ripples.min.css" rel="stylesheet">
    <!-- Google Material Icons -->
    {{--<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">--}}

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

    {{ HTML::style('css/style.css') }}

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

{{ $menuWidget->mainMenu }}

<div class="container">
    <div class="row">

        <div class="col-lg-3 col-md-3 hidden-sm hidden-xs">
            @yield('leftSidebar')
            {{ $areaWidget->leftSidebar() }}
        </div>

        <div class="col-lg-9 col-md-9">
            @yield('content')
        </div>

        <div class="col-sm-6 col-xs-12 hidden-lg hidden-md">
            @yield('leftSidebar')
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

@include('footer')

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
        $('[data-toggle="tooltip"]').tooltip();

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

<script>
    $(function() {
        $.material.init();
    });
</script>

@yield('script')

</body>
</html>