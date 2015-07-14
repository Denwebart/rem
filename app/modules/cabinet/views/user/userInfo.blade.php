<div>
    <div class="avatar">
        {{ $user->getAvatar() }}
    </div>

    @if($user->is_banned)
        <div class="banned">
            {{ HTML::image(Config::get('settings.bannedImage'),
            'Забанен ' . DateHelper::dateFormat($user->latestBanNotification->ban_at) . '. Причина бана: "' . $user->latestBanNotification->message . '"',
            [
                'class' => 'img-responsive',
                'title' => 'Забанен ' . DateHelper::dateFormat($user->latestBanNotification->ban_at) . '. Причина бана: "' . $user->latestBanNotification->message . '"'
            ]) }}
        </div>
    @endif

    @if(Auth::check())
        @if(!Auth::user()->is($user))
            <a href="{{ URL::route('user.dialog', ['login' => Auth::user()->getLoginForUrl(), 'companion' => $user->getLoginForUrl()]) }}" class="btn btn-primary">
                Написать личное сообщение
            </a>
        @endif
        @if(Auth::user()->isAdmin())
            @include('widgets.ban', ['user' => $user])
        @endif
    @endif
</div>