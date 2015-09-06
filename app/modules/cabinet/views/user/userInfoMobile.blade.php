<div class="col-sm-12 col-xs-12">
    <div id="user-info-mobile">
        <div class="pull-right">
            @if(Auth::check())
                @if(Auth::user()->isAdmin())
                    @include('widgets.ban', ['user' => $user])
                @endif
                @if(!Auth::user()->is($user))
                    <a href="{{ URL::route('user.dialog', ['login' => Auth::user()->getLoginForUrl(), 'companion' => $user->getLoginForUrl()]) }}" class="btn btn-primary btn-sm send-message pull-left">
                        <i class="material-icons">send</i>
                        Написать <span class="hidden-xs">сообщение</span>
                    </a>
                @endif
            @endif
        </div>
        <a class="pull-left avatar-link gray-background @if($user->is_banned) banned @endif" href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
            {{ $user->getAvatar('mini', ['class' => 'media-object avatar circle']) }}
            @if($user->isOnline())
                <span class="is-online-status online" title="Сейчас на сайте" data-toggle="tooltip" data-placement="top"></span>
            @else
                <span class="is-online-status offline" title="Офлайн. Последний раз был {{ DateHelper::getRelativeTime($user->last_activity) }}" data-toggle="tooltip" data-placement="top"></span>
            @endif
        </a>
        @if(Auth::check())
            @if(!Auth::user()->is($user))
                <div class="profile-user-status pull-left">
                    @if($user->isOnline())
                        <span class="text pull-left">Сейчас на сайте</span>
                    @else
                        <span class="text pull-left">
                            Был на сайте
                            <span title="{{ DateHelper::dateFormat($user->last_activity) }}" data-toggle="tooltip" data-placement="top">
                                {{ DateHelper::getRelativeTime($user->last_activity) }}
                            </span>
                        </span>
                    @endif
                </div>
            @endif
        @endif
        @if($user->is_banned)
            <br>
            <span class="banned-text pull-left label label-danger">
                Забанен
            </span>
        @endif
    </div>
    <div class="clearfix"></div>
</div>

<div class="col-sm-12 col-xs-12" id="users-menu-mobile">
    @include('cabinet::user.menuMobile')
</div>