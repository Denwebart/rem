<div class="row item" data-message-id="{{ $message->id }}">
    <div class="col-md-2">
        <a href="{{ URL::route('user.profile', ['login' => $message->userSender->getLoginForUrl()]) }}" class="pull-right avatar-link gray-background display-inline-block">
            {{ $message->userSender->getAvatar('mini', ['class' => 'avatar circle']) }}
            @if($message->userSender->isOnline())
                <span class="is-online-status online" title="Сейчас на сайте"></span>
            @else
                <span class="is-online-status offline" title="Последний раз был {{ DateHelper::getRelativeTime($message->userSender->last_activity) }}"></span>
            @endif
        </a>
    </div>
    <div class="col-md-7">
        <div class="message">
            <span class="date">
                {{ DateHelper::dateForMessage($message->created_at) }}
            </span>
            <div class="clearfix"></div>
            {{ StringHelper::addFancybox($message->message, 'group-message-' . $message->id) }}
        </div>
    </div>
    <div class="col-md-2"></div>
</div>