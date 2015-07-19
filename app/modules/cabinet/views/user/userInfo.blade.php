<div class="row" id="user-info">
    <div class="col-md-10" style="padding-right: 0">
        <div class="avatar">
            <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                {{ $user->getAvatar() }}
            </a>
        </div>
    </div>
    <div class="col-md-2" style="padding: 0">
        <div class="honors">
            @foreach($user->honors as $honor)
                <a href="{{ URL::route('honor.info', ['alias' => $honor->alias]) }}">
                    {{ $honor->getImage(null, ['width' => '25px', 'title' => $honor->title, 'alt' => $honor->title]) }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="col-md-12">
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
</div>
<div class="clearfix"></div>