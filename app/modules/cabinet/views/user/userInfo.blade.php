<div class="row" id="user-info">
    <div class="col-md-10" style="padding-right: 0">
        <div class="avatar">
            <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                {{ $user->getAvatar() }}
            </a>
        </div>
        <div class="status">
            @if($user->isOnline())
                <i class="material-icons mdi-success" title="Онлайн" data-toggle="tooltip" data-placement="top">lens</i>
                Сейчас на сайте
            @else
                <i class="material-icons mdi-default" title="Офлайн" data-toggle="tooltip" data-placement="top">lens</i>
                Последний раз был
                <span title="{{ DateHelper::dateFormat($user->last_activity) }}" data-toggle="tooltip" data-placement="top">
                    {{ DateHelper::getRelativeTime($user->last_activity) }}
                </span>
            @endif
        </div>
    </div>
    <div class="col-md-2" style="padding: 0">
        <div class="honors">
            @foreach($user->userHonors as $userHonor)
                <a href="{{ URL::route('honor.info', ['alias' => $userHonor->honor->alias]) }}">
                    {{ $userHonor->honor->getImage(null, [
                        'width' => '25px',
                        'title' => !is_null($userHonor->comment)
                            ? $userHonor->honor->title . ' ('. $userHonor->comment .')'
                            : $userHonor->honor->title,
                        'alt' => $userHonor->honor->title])
                    }}
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