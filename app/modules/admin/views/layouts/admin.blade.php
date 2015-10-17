<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @if(isset($title))
            {{ $title }}
            -
        @endif
        Административная панель сайта Avtorem.info
    </title>

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

    <!-- Maniac stylesheets -->
    <link rel="stylesheet" href="/backend/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/backend/css/font-awesome.min.css" />
    <link rel="stylesheet" href="/backend/css/animate/animate.min.css" />
    <link rel="stylesheet" href="/backend/css/iCheck/all.css" />
    <link rel="stylesheet" href="/backend/css/style.css" />
    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    @yield('style')

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="fixed">
<!-- Header -->
    {{ $headerWidget->show() }}
<!-- /.header -->

<!-- wrapper -->
<div class="wrapper">
    <div class="leftside">
        <div class="sidebar">
            <div class="user-box">
                <div class="avatar">
                    <a href="{{ URL::route('user.profile', ['login' => Auth::user()->getLoginForUrl()]) }}">
                        {{ Auth::user()->getAvatar('mini', ['class' => 'pull-left']) }}
                    </a>
                </div>
                <div class="details">
                    <p>
                        <a href="{{ URL::route('user.profile', ['login' => Auth::user()->getLoginForUrl()]) }}">
                            {{ Auth::user()->login }}
                        </a>
                    </p>
                    <span class="position">{{ User::$roles[Auth::user()->role] }}</span>
                </div>
                {{--<div class="button">--}}
                    {{--<a href="{{ URL::route('logout') }}"><i class="fa fa-power-off"></i></a>--}}
                {{--</div>--}}
            </div>
            <ul class="sidebar-menu">
                <li class="title">Навигация</li>
                <li class="{{ (URL::to('admin') != URL::current()) ? '' : 'active'}}">
                    <a href="{{ URL::to('admin') }}">
                        <i class="fa fa-home"></i> <span>Главная</span>
                    </a>
                </li>
                <li class="{{ Request::is('admin/pages*') ? 'active' : ''}}">
                    <a href="{{ URL::route('admin.pages.index') }}">
                        <i class="fa fa-file"></i> <span>Страницы</span>
                    </a>
                </li>
                <li class="{{ Request::is('admin/questions*') ? 'active' : ''}}">
                    <a href="{{ URL::route('admin.questions.index') }}">
                        <i class="fa fa-question"></i> <span>Вопросы</span>
                        @if($newQuestions = count($headerWidget->newQuestions))
                            <small class="label pull-right">
                                {{ $newQuestions }}
                            </small>
                        @endif
                    </a>
                </li>
                <li class="{{ Request::is('admin/articles*') ? 'active' : ''}}">
                    <a href="{{ URL::route('admin.articles.index') }}">
                        <i class="fa fa-file-o"></i> <span>Статьи юзеров</span>
                        @if($newArticles = count($headerWidget->newArticles))
                            <small class="label pull-right">
                                {{ $newArticles }}
                            </small>
                        @endif
                    </a>
                </li>
                <li class="{{ Request::is('admin/comments*') ? 'active' : ''}}">
                    <a href="{{ URL::route('admin.comments.index') }}">
                        <i class="fa fa-comment"></i> <span>Комментарии</span>
                        @if($newComments = count($headerWidget->newComments))
                            <small class="label pull-right">
                                {{ $newComments }}
                            </small>
                        @endif
                    </a>
                </li>
                <li class="{{ Request::is('admin/tags*') ? 'active' : ''}}">
                    <a href="{{ URL::route('admin.tags.index') }}">
                        <i class="fa fa-tags"></i> <span>Теги</span>
                    </a>
                </li>
                @if(Auth::user()->isAdmin())
                    <li class="{{ Request::is('admin/letters*') ? 'active' : ''}}">
                        <a href="{{ URL::route('admin.letters.index') }}">
                            <i class="fa fa-envelope"></i> <span>Письма</span>
                            @if($newLetters = count($headerWidget->newLetters))
                                <small class="label pull-right">
                                    {{ $newLetters }}
                                </small>
                            @endif
                        </a>
                    </li>
                    <li class="{{ Request::is('admin/users*') ? 'active' : ''}}">
                        <a href="{{ URL::route('admin.users.index') }}">
                            <i class="fa fa-users"></i> <span>Пользователи</span>
                            @if($newUsers = count($headerWidget->newUsers))
                                <small class="label pull-right">
                                    {{ $newUsers }}
                                </small>
                            @endif
                        </a>
                    </li>
                    <li class="{{ Request::is('admin/honors*') ? 'active' : ''}}">
                        <a href="{{ URL::route('admin.honors.index') }}">
                            <i class="fa fa-trophy"></i> <span>Награды</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('admin/advertising*') ? 'active' : ''}}">
                        <a href="{{ URL::route('admin.advertising.index') }}">
                            <i class="fa fa-usd"></i> <span>Реклама и виджеты</span>
                        </a>
                    </li>
                    <li class="{{ Request::is('admin/settings*') ? 'active' : ''}}">
                        <a href="{{ URL::route('admin.settings.index') }}">
                            <i class="fa fa-cogs"></i> <span>Настройки</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    <div class="rightside">
        @yield('content')
    </div>

</div><!-- /.wrapper -->

<!-- Javascript -->
<script src="/backend/js/plugins/jquery/jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="/backend/js/plugins/jquery-ui/jquery-ui-1.10.4.min.js" type="text/javascript"></script>

<!-- Bootstrap -->
<script src="/backend/js/plugins/bootstrap/bootstrap.min.js" type="text/javascript"></script>

<!-- Interface -->
<script src="/backend/js/plugins/jquery-countTo/jquery.countTo.js" type="text/javascript"></script>
<script src="/backend/js/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>

<!-- Forms -->
<script src="/backend/js/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
<script src="/backend/js/custom.js" type="text/javascript"></script>

<!-- Dashboard -->
<script type="text/javascript">
    (function($) {
        "use strict";

        //iCheck
        $("input[type='checkbox'], input[type='radio']").iCheck({
            checkboxClass: 'icheckbox_minimal',
            radioClass: 'iradio_minimal'
        });

        function showTooltip(x, y, contents) {
            $("<div id='flot_tip'>" + contents + "</div>").css({
                top: y - 20,
                left: x + 5
            }).appendTo("body").fadeIn(200);
        }
    })(jQuery);
</script>

@yield('script')

</body>
</html>