<ul>
    <li class="{{ (Route::is('user.profile') || Route::is('user.edit') || Route::is('user.changePassword')) ? 'active' : '' }}">
        <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
            <i class="material-icons">account_box</i>
            <span>{{ Auth::check() ? (Auth::user()->is($user) ? 'Мой профиль' : 'Профиль') : 'Профиль' }}</span>
        </a>
    </li>
    <li class="{{ (Route::is('user.gallery') || Route::is('user.gallery.editPhoto')) ? 'active' : '' }}">
        <a href="{{ URL::route('user.gallery', ['login' => $user->getLoginForUrl()]) }}">
            <i class="material-icons">directions_car</i>
            <span>{{ Auth::check() ? (Auth::user()->is($user) ? 'Мой автомобиль' : 'Aвтомобиль') : 'Автомобиль' }}</span>
        </a>
    </li>
    <li class="questions {{ (Route::is('user.questions') || Route::is('user.questions.edit') || Route::is('user.questions.create')) ? 'active' : '' }}">
        <a href="{{ URL::route('user.questions', ['login' => $user->getLoginForUrl()]) }}">
            <i class="material-icons">help</i>
            <span>{{ Auth::check() ? (Auth::user()->is($user) ? 'Мои вопросы' : 'Вопросы') : 'Вопросы' }}</span>
        </a>
    </li>
    <li class="articles {{ (Route::is('user.journal') || Route::is('user.journal.edit') || Route::is('user.journal.create')) ? 'active' : '' }}">
        <a href="{{ URL::route('user.journal', ['journalAlias' => Config::get('settings.journalAlias'), 'login' => $user->getLoginForUrl()]) }}">
            <i class="material-icons">chrome_reader_mode</i>
            <span>{{ Auth::check() ? (Auth::user()->is($user) ? 'Мой журнал' : 'Бортовой журнал') : 'Бортовой журнал' }}</span>
        </a>
    </li>
    <li class="comments {{ Route::is('user.comments') ? 'active' : '' }}">
        <a href="{{ URL::route('user.comments', ['login' => $user->getLoginForUrl()]) }}">
            <i class="material-icons">chat_bubble</i>
            <span>{{ Auth::check() ? (Auth::user()->is($user) ? 'Мои комментарии' : 'Комментарии') : 'Комментарии' }}</span>
        </a>
    </li>
    <li class="answers {{ Route::is('user.answers') ? 'active' : '' }}">
        <a href="{{ URL::route('user.answers', ['login' => $user->getLoginForUrl()]) }}">
            <i class="material-icons">question_answer</i>
            <span>{{ Auth::check() ? (Auth::user()->is($user) ? 'Мои ответы' : 'Ответы') : 'Ответы' }}</span>
        </a>
    </li>
    @if(Auth::check())
        @if(Auth::user()->is($user) || Auth::user()->isAdmin())
            <li class="messages {{ (Route::is('user.messages') || Route::is('user.dialog')) ? 'active' : '' }}">
                <a href="{{ URL::route('user.messages', ['login' => $user->getLoginForUrl()]) }}">
                    <i class="material-icons">send</i>
                    <span class="hidden-md hidden-sm">Личные сообщения</span>
                    <span class="hidden-lg hidden-xs">Сообщения</span>
                    @if(Auth::user()->is($user))
                        @if(count($headerWidget->newMessages))
                            <small class="label label-info">{{ $headerWidget->newMessages->getTotal() }}</small>
                        @endif
                    @endif
                </a>
            </li>
            <li class="saved {{ Route::is('user.savedPages') ? 'active' : '' }}">
                <a href="{{ URL::route('user.savedPages', ['login' => $user->getLoginForUrl()]) }}">
                    <i class="material-icons">archive</i>
                    <span>Сохранённое</span>
                </a>
            </li>
        @endif
        <li class="subscriptions {{ Route::is('user.subscriptions') ? 'active' : '' }}">
            <a href="{{ URL::route('user.subscriptions', ['login' => $user->getLoginForUrl()]) }}">
                <i class="material-icons">local_library</i>
                <span>{{ Auth::user()->is($user) ? 'Мои подписки' : 'Подписки'}}</span>
                @if(Auth::user()->is($user))
                    @if($newSubscriptionsNotifications = count($headerWidget->newSubscriptionsNotifications()))
                        <small class="label label-info">{{ $newSubscriptionsNotifications }}</small>
                    @endif
                @endif
            </a>
        </li>
        @if(Auth::user()->is($user) || Auth::user()->isAdmin())
            <li class="notifications {{ Route::is('user.notifications') ? 'active' : '' }}">
                <a href="{{ URL::route('user.notifications', ['login' => $user->getLoginForUrl()]) }}">
                    <i class="material-icons">notifications</i>
                    <span class="hidden-md hidden-sm">
                        {{ Auth::user()->is($user) ? 'Мои уведомления' : 'Уведомления'}}
                    </span>
                    <span class="hidden-lg hidden-xs">
                        Уведомления
                    </span>
                    @if(Auth::user()->is($user))
                        @if(count($headerWidget->newNotifications))
                            <small class="label label-info">{{ $headerWidget->newNotifications->getTotal() }}</small>
                        @endif
                    @endif
                </a>
            </li>
        @endif
    @endif
</ul>