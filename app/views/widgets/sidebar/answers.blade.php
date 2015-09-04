<div id="answers-sidebar-widget" class="list-group sidebar-widget">
    @foreach($answers as $key => $answer)
        @if($key != 0)
            <div class="list-group-separator"></div>
        @endif
        <div class="list-group-item">
            <div class="row-picture">
                @if($answer->user)
                    <a href="{{ URL::route('user.profile', ['login' => $answer->user->getLoginForUrl()]) }}" class="avatar-link gray-background">
                        {{ $answer->user->getAvatar('mini', ['class' => 'avatar circle']) }}
                        @if($answer->user->isOnline())
                            <span class="is-online-status online" title="Сейчас на сайте" data-toggle="tooltip" data-placement="top"></span>
                        @else
                            <span class="is-online-status offline" title="Офлайн. Последний раз был {{ DateHelper::getRelativeTime($answer->user->last_activity) }}" data-toggle="tooltip" data-placement="top"></span>
                        @endif
                    </a>
                @else
                    <a href="{{ URL::to($answer->getUrl()) }}">
                        {{ (new User)->getAvatar('mini', ['class' => 'avatar circle']) }}
                    </a>
                @endif
            </div>
            <div class="row-content">
                <div class="created-date pull-right">
                    <span class="relative-date date" title="{{ DateHelper::dateFormat($answer->created_at) }}">
                        {{ DateHelper::getRelativeTime($answer->created_at) }}
                    </span>
                </div>
                @if($answer->user)
                    <span class="login pull-left">{{ $answer->user->login }}</span>
                @else
                    <span class="login pull-left">{{ $answer->user_name }}</span>
                @endif
                <p class="list-group-item-text" style="clear: both">
                    <a href="{{ URL::to($answer->getUrl()) }}">
                        {{ StringHelper::limit($answer->comment, 70) }}
                    </a>
                </p>
            </div>
        </div>
    @endforeach
</div>