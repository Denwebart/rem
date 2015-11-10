<div id="companions">
    <div class="header">
        <h3>Собеседники</h3>
    </div>
    <div class="body">
        @if(count($companions))
            @foreach($companions as $item)
                <div class="companion {{ ($companionId == $item->id) ? ' active' : '' }}" data-user-id="{{ $item->id }}">
                    <a href="{{ URL::route('user.dialog', ['login' => $user->getLoginForUrl(), 'companion' => $item->getLoginForUrl()]) }}">
                        <span class="avatar-link gray-background pull-left">
                            {{ $item->getAvatar('mini', ['class' => 'avatar circle', 'data-placement' => 'right']) }}
                            @if($item->isOnline())
                                <span class="is-online-status online" title="Сейчас на сайте" data-toggle="tooltip" data-placement="right"></span>
                            @else
                                <span class="is-online-status offline" title="Последний раз был {{ DateHelper::getRelativeTime($item->last_activity) }}" data-toggle="tooltip" data-placement="right"></span>
                            @endif
                        </span>
                        <span class="login-link">
                            <span class="hidden-sm hidden-xs">{{ $item->login }}</span>
                            <small class="label label-info pull-right" @if(!$numberOfMessages = count($item->sentMessagesForUser)) style="display: none;" @endif>
                                @if(count($item->sentMessagesForUser))
                                    {{ $numberOfMessages }}
                                @endif
                            </small>
                        </span>
                    </a>
                </div>
            @endforeach
        @else
            <p>
                Вы еще ни с кем не переписывались.
            </p>
        @endif
    </div>
</div>