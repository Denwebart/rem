<header id="header-widget">
    @if(Request::is('admin*'))
        <a href="{{ URL::to('admin') }}" class="logo"><i class="fa fa-wrench"></i> <span>Админка</span></a>
    @endif
    <nav class="navbar navbar-static-top">
        @if(!Request::is('admin*') && (Auth::user()->isAdmin() || (Auth::user()->isModerator() && !Auth::user()->is_banned )))
            <a href="{{ URL::to('admin') }}" class="logo"><i class="fa fa-wrench"></i> <span>Админка</span></a>
        @elseif(!Request::is('admin*') && (!Auth::user()->isAdmin() || !Auth::user()->isModerator()))
            <div class="logo"></div>
        @endif
        @if(Request::is('admin*'))
            <a href="#" class="navbar-btn sidebar-toggle">
                <span class="sr-only">Навигация</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="navbar-header">
                <form role="search" class="navbar-form" method="post" action="#">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Поиск..."/>
                        <span class="input-group-btn">
                            <button type="submit" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                        </span>
                    </div>
                </form>
            </div>
        @endif
        <div class="navbar-left">
            <ul class="nav navbar-nav">
                <li style="margin-right: 10px">
                    <a href="{{ URL::route('users') }}" class="">
                        <span>
                            <i class="fa fa-users"></i>
                            Все пользователи
                        </span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="navbar-right">
            <ul class="nav navbar-nav">

                @if(Auth::user()->isAdmin() && !Request::is('admin*'))
                    <li style="margin-right: 10px">
                        <a href="javascript:void(0)" id="edit-advertising" title="Редактировать рекламу">
                            <span>
                                <i class="fa fa-dollar"></i>
                            </span>
                        </a>
                    </li>
                @endif

                @if(!is_null($page) && Auth::user()->isAdmin())
                    <li style="margin-right: 10px">
                        <a href="{{ URL::route('admin.pages.edit', ['id' => $page->id]) }}" class="">
                            <span>
                                <i class="fa fa-edit"></i>
                                Редактировать
                            </span>
                        </a>
                    </li>
                @endif

                {{--<li class="dropdown dropdown-notifications">--}}
                    {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
                        {{--<i class="fa fa-bell"></i><span class="label label-warning">5</span>--}}
                    {{--</a>--}}
                    {{--<ul class="dropdown-menu">--}}
                        {{--<li class="header"><i class="fa fa-bell"></i>  You have 5 new notifications</li>--}}
                        {{--<li>--}}
                            {{--<ul>--}}
                                {{--<li><a href="#"><i class="fa fa-users success"></i> New user registered</a></li>--}}
                                {{--<li><a href="#"><i class="fa fa-heart info"></i> Jane liked your post</a></li>--}}
                                {{--<li><a href="#"><i class="fa fa-envelope warning"></i> You got a message from Jean</a></li>--}}
                                {{--<li><a href="#"><i class="fa fa-info success"></i> Privacy settings have been changed</a></li>--}}
                                {{--<li><a href="#"><i class="fa fa-comments danger"></i> New comments waiting approval</a></li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                        {{--<li class="footer"><a href="#">View all notification</a></li>--}}
                    {{--</ul>--}}
                {{--</li>--}}

                @if(Request::is('admin*'))
                    <li style="margin-right: 10px">
                        <a href="{{ URL::to('/') }}" class="" target="_blank">
                        <span>
                            <i class="glyphicon glyphicon-arrow-right"></i>
                            Перейти на сайт
                        </span>
                        </a>
                    </li>
                @endif

                {{ $messages }}

                @if(Auth::user()->isAdmin())
                    {{ $letters }}
                @endif

                {{--<li class="dropdown dropdown-tasks">--}}
                    {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
                        {{--<i class="fa fa-tasks"></i><span class="label label-danger">1</span>--}}
                    {{--</a>--}}
                    {{--<ul class="dropdown-menu">--}}
                        {{--<li class="header"><i class="fa fa-tasks"></i>  You have 1 new task</li>--}}
                        {{--<li>--}}
                            {{--<ul>--}}
                                {{--<li>--}}
                                    {{--<a href="#">--}}
                                        {{--<h3>PHP Developing<small class="pull-right">32%</small></h3>--}}
                                        {{--<div class="progress">--}}
                                            {{--<div class="progress-bar progress-bar-success" style="width: 32%" role="progressbar" aria-valuenow="32" aria-valuemin="0" aria-valuemax="100"></div>--}}
                                        {{--</div>--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a href="#">--}}
                                        {{--<h3>Database Repair<small class="pull-right">14%</small></h3>--}}
                                        {{--<div class="progress">--}}
                                            {{--<div class="progress-bar progress-bar-warning" style="width: 14%" role="progressbar" aria-valuenow="14" aria-valuemin="0" aria-valuemax="100"></div>--}}
                                        {{--</div>--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a href="#">--}}
                                        {{--<h3>Backup Create<small class="pull-right">65%</small></h3>--}}
                                        {{--<div class="progress">--}}
                                            {{--<div class="progress-bar progress-bar-info" style="width: 65%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>--}}
                                        {{--</div>--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a href="#">--}}
                                        {{--<h3>Create New Modules<small class="pull-right">80%</small></h3>--}}
                                        {{--<div class="progress">--}}
                                            {{--<div class="progress-bar progress-bar-danger" style="width: 80%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>--}}
                                        {{--</div>--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                        {{--<li class="footer">--}}
                            {{--<a href="#">View all tasks</a>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                {{--</li>--}}

                <li class="dropdown widget-user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        {{ $user->getAvatar('mini', ['class' => 'pull-left']) }}
                        <span>{{ $user->login }} <i class="fa fa-caret-down"></i></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl() ]) }}"><i class="fa fa-user"></i>Мой профиль</a>
                        </li>
                        <li>
                            <a href="{{ URL::route('user.gallery', ['login' => $user->getLoginForUrl() ]) }}"><i class="fa fa-car"></i>Мой автомобиль</a>
                        </li>
                        <li>
                            <a href="{{ URL::route('user.questions', ['login' => $user->getLoginForUrl() ]) }}"><i class="fa fa-question"></i>Мои вопросы</a>
                        </li>
                        <li>
                            <a href="{{ URL::route('user.journal', ['login' => $user->getLoginForUrl() ]) }}"><i class="fa fa-book"></i>Мой журнал</a>
                        </li>
                        <li>
                            <a href="{{ URL::route('user.comments', ['login' => $user->getLoginForUrl() ]) }}"><i class="fa fa-comment"></i>Мои комментарии</a>
                        </li>
                        <li>
                            <a href="{{ URL::route('user.messages', ['login' => $user->getLoginForUrl() ]) }}"><i class="fa fa-send"></i>Личные сообщения</a>
                        </li>
                        <li>
                            <a href="{{ URL::route('user.savedPages', ['login' => $user->getLoginForUrl() ]) }}"><i class="glyphicon glyphicon-floppy-disk"></i>Сохранённое</a>
                        </li>
                        <li>
                            <a href="{{ URL::route('user.subscriptions', ['login' => $user->getLoginForUrl() ]) }}"><i class="fa fa-heart"></i>Мои подписки</a>
                        </li>
                        <li class="footer">
                            <a href="{{ URL::route('logout') }}"><i class="fa fa-power-off"></i>Выход</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

@section('script')
    @parent

    @if(Auth::user()->isAdmin() && !Request::is('admin*'))
        <script type="text/javascript">
            $(document).ready(function() {
                <!-- Edit advertising -->
                $('#edit-advertising').on('click', function(){
                    if ($(this).hasClass('active')) {
                        $(this).parent().removeClass('open');
                        $('.advertising, .area').removeClass('edit');
                        $(this).removeClass('active');
                        $('.area .buttons, .area .area-title, .advertising-title').hide();
                        $('.advertising.not-active, .advertising.access-3').hide();
                    } else {
                        $(this).parent().addClass('open');
                        $('.advertising, .area').addClass('edit');
                        $(this).addClass('active');
                        $('.area .buttons, .area .area-title, .advertising-title').show();
                        $('.advertising').show();
                    }
                });
            });
        </script>
    @endif
@endsection