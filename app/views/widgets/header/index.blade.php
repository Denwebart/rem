<header id="header-widget">
    @if(Request::is('admin*'))
        <a href="{{ URL::to('admin') }}" class="logo">
            <i class="material-icons">build</i>
            <span>Админка</span>
        </a>
    @endif
    <nav class="navbar navbar-static-top">
        @if(!Request::is('admin*') && (Auth::user()->isAdmin() || (Auth::user()->isModerator() && !Auth::user()->is_banned )))
            <a href="{{ URL::to('admin') }}" class="logo">
                <i class="material-icons">build</i>
                <span>Админка</span>
            </a>
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
                            <button type="submit" id="search-btn" class="btn btn-flat">
                                <i class="material-icons">search</i>
                            </button>
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
                            <i class="material-icons">group</i>
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
                                <i class="material-icons">attach_money</i>
                            </span>
                        </a>
                    </li>
                @endif

                @if(!is_null($page) && Auth::user()->isAdmin())
                    <li style="margin-right: 10px">
                        <a href="{{ URL::route('admin.pages.edit', ['id' => $page->id, 'backUrl' => urlencode(Request::url())]) }}" class="">
                            <span>
                                <i class="material-icons">mode_edit</i>
                                Редактировать
                            </span>
                        </a>
                    </li>
                @endif

                <li class="dropdown dropdown-notifications">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="material-icons">notifications</i>
                        <span class="label label-warning">5</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">
                            <i class="material-icons">notifications</i>You have 5 new notifications
                        </li>
                        <li>
                            <ul>
                                <li><a href="#"><i class="fa fa-users success"></i> New user registered</a></li>
                                <li><a href="#"><i class="fa fa-heart info"></i> Jane liked your post</a></li>
                                <li><a href="#"><i class="fa fa-envelope warning"></i> You got a message from Jean</a></li>
                                <li><a href="#"><i class="fa fa-info success"></i> Privacy settings have been changed</a></li>
                                <li><a href="#"><i class="fa fa-comments danger"></i> New comments waiting approval</a></li>
                            </ul>
                        </li>
                        <li class="footer"><a href="#">View all notification</a></li>
                    </ul>
                </li>

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

                <li class="dropdown widget-user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        {{ $user->getAvatar('mini', ['class' => 'pull-left']) }}
                        <span>
                            {{ $user->login }}
                            <i class="material-icons">arrow_drop_down</i>
                        </span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl() ]) }}">
                                <i class="material-icons">account_box</i>
                                Мой профиль
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::route('user.gallery', ['login' => $user->getLoginForUrl() ]) }}">
                                <i class="material-icons">directions_car</i>
                                Мой автомобиль
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::route('user.questions', ['login' => $user->getLoginForUrl() ]) }}">
                                <i class="material-icons">help</i>
                                Мои вопросы
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::route('user.journal', ['journalAlias' => Config::get('settings.journalAlias'), 'login' => $user->getLoginForUrl()]) }}">
                                <i class="material-icons">chrome_reader_mode</i>
                                Мой журнал
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::route('user.comments', ['login' => $user->getLoginForUrl() ]) }}">
                                <i class="material-icons">chat_bubble</i>
                                Мои комментарии
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::route('user.answers', ['login' => $user->getLoginForUrl() ]) }}">
                                <i class="material-icons">question_answer</i>
                                Мои ответы
                            </a>
                        </li>

                        <li>
                            <a href="{{ URL::route('user.messages', ['login' => $user->getLoginForUrl() ]) }}">
                                <i class="material-icons">send</i>
                                Личные сообщения
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::route('user.savedPages', ['login' => $user->getLoginForUrl() ]) }}">
                                <i class="material-icons">archive</i>
                                Сохранённое
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::route('user.subscriptions', ['login' => $user->getLoginForUrl() ]) }}">
                                <i class="material-icons">local_library</i>
                                Мои подписки
                            </a>
                        </li>
                        <li class="footer">
                            <a href="{{ URL::route('logout') }}">
                                <i class="material-icons">power_settings_new</i>
                                Выход
                            </a>
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