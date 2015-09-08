<header id="header-widget">
    @if(Request::is('admin*'))
        <a href="{{ URL::to('admin') }}" class="logo">
            <i class="material-icons">build</i>
            <span class="hidden-xs">Админка</span>
        </a>
    @endif
    <nav class="navbar navbar-static-top">
        @if(!Request::is('admin*') && (Auth::user()->isAdmin() || (Auth::user()->isModerator() && !Auth::user()->is_banned )))
            <a href="{{ URL::to('admin') }}" class="logo">
                <i class="material-icons">build</i>
                <span class="hidden-xs">Админка</span>
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
            {{--<div class="navbar-header">--}}
                {{--<form role="search" class="navbar-form" method="post" action="#">--}}
                    {{--<div class="input-group">--}}
                        {{--<input type="text" class="form-control" placeholder="Поиск..."/>--}}
                        {{--<span class="input-group-btn">--}}
                            {{--<button type="submit" id="search-btn" class="btn btn-flat">--}}
                                {{--<i class="material-icons">search</i>--}}
                            {{--</button>--}}
                        {{--</span>--}}
                    {{--</div>--}}
                {{--</form>--}}
            {{--</div>--}}
        @endif
        <div class="navbar-left">
            <ul class="nav navbar-nav">
                <li style="margin-right: 10px">
                    <a href="{{ URL::route('users') }}" class="">
                        <span>
                            <i class="material-icons">group</i>
                            <span class="hidden-sm hidden-xs margin-left-5">Все пользователи</span>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="navbar-right">
            <ul class="nav navbar-nav">

                @if(Auth::user()->isAdmin() && !Request::is('admin*'))
                    <li class="edit-advertising" style="margin-right: 10px">
                        <a href="javascript:void(0)" id="edit-advertising" title="Редактировать рекламу" data-toggle="tooltip" data-placement="bottom">
                            <i class="material-icons">attach_money</i>
                        </a>
                    </li>
                @endif

                @if(!is_null($page) && Auth::user()->isAdmin())
                    <li class="edit-page" style="margin-right: 10px">
                        <a href="{{ URL::route('admin.pages.edit', ['id' => $page->id, 'backUrl' => urlencode(Request::url())]) }}" title="Редактировать эту страницу" data-toggle="tooltip" data-placement="bottom">
                            <i class="material-icons">edit</i>
                            <span class="hidden-sm hidden-xs">Редактировать</span>
                        </a>
                    </li>
                @endif

                @if(Request::is('admin*'))
                    <li style="margin-right: 10px" class="on-site">
                        <a href="{{ URL::to('/') }}" target="_blank">
                            <span>
                                <span class="hidden-sm hidden-xs">Перейти на сайт</span>
                                <span class="hidden-md hidden-lg">На сайт</span>
                                <i class="material-icons pull-right">chevron_right</i>
                            </span>
                        </a>
                    </li>
                @endif

                {{ $notifications }}

                {{ $messages }}

                @if(Auth::user()->isAdmin())
                    {{ $letters }}
                @endif

                <li class="dropdown widget-user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        {{ $user->getAvatar('mini', ['class' => 'pull-left avatar']) }}
                        <span>
                            <span class="hidden-xs">{{ $user->login }}</span>
                            <i class="material-icons pull-right">arrow_drop_down</i>
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
                        <li>
                            <a href="{{ URL::route('user.notifications', ['login' => $user->getLoginForUrl() ]) }}">
                                <i class="material-icons">notifications</i>
                                Мои уведомления
                            </a>
                        </li>
                        <li class="footer">
                            @if(isset($backUrlLogout))
                                <a href="{{ URL::route('logout', ['backUrl' => urlencode($backUrlLogout)]) }}">
                                    <i class="material-icons">power_settings_new</i>
                                    Выход
                                </a>
                            @else
                                <a href="{{ URL::route('logout') }}">
                                    <i class="material-icons">power_settings_new</i>
                                    Выход
                                </a>
                            @endif
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