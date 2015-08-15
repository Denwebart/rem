<div id="companions">
    <div class="header">
        <h3>Собеседники</h3>
    </div>
    <div class="body">
        @foreach($companions as $item)
            <div class="companion {{ ($companionId == $item->id) ? ' active' : '' }}" data-user-id="{{ $item->id }}">
                <a href="{{ URL::route('user.dialog', ['login' => $user->getLoginForUrl(), 'companion' => $item->getLoginForUrl()]) }}" class="avatar-link gray-background pull-left">
                    {{ $item->getAvatar('mini', ['class' => 'avatar circle']) }}
                    @if($item->isOnline())
                        <span class="is-online-status online" title="Сейчас на сайте" data-toggle="tooltip" data-placement="top"></span>
                    @else
                        <span class="is-online-status offline" title="Офлайн. Последний раз был {{ DateHelper::getRelativeTime($item->last_activity) }}" data-toggle="tooltip" data-placement="top"></span>
                    @endif
                </a>
                <a href="{{ URL::route('user.dialog', ['login' => $user->getLoginForUrl(), 'companion' => $item->getLoginForUrl()]) }}" class="login-link">
                    <span>{{ $item->login }}</span>
                    @if($numberOfMessages = count($item->sentMessagesForUser))
                        <small class="label label-info pull-right">{{ $numberOfMessages }}</small>
                    @endif
                </a>
            </div>
        @endforeach
    </div>
</div>