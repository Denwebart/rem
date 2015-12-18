<div id="comments-sidebar-widget" class="list-group sidebar-widget">
    @foreach($comments as $key => $comment)
        @if($key != 0)
            <div class="list-group-separator"></div>
        @endif
        <div class="list-group-item">
            <div class="row-picture">
                @if($comment->user)
                    <a href="{{ URL::route('user.profile', ['login' => $comment->user->getLoginForUrl()]) }}" class="avatar-link gray-background">
                        {{ $comment->user->getAvatar('mini', ['class' => 'avatar circle', 'data-placement' => 'top']) }}
                        @if($comment->user->isOnline())
                            <span class="is-online-status online" title="Сейчас на сайте" data-toggle="tooltip" data-placement="right"></span>
                        @else
                            <span class="is-online-status offline" title="Последний раз был {{ DateHelper::getRelativeTime($comment->user->last_activity) }}" data-toggle="tooltip" data-placement="right"></span>
                        @endif
                    </a>
                @else
                    <a href="{{ URL::to($comment->getUrl()) }}">
                        {{ (new User)->getAvatar('mini', ['class' => 'avatar circle', 'data-placement' => 'top']) }}
                    </a>
                @endif
            </div>
            <div class="row-content">
                <div class="created-date pull-right">
                    <span class="relative-date" title="{{ DateHelper::dateFormat($comment->created_at) }}" data-toggle="tooltip" data-placement="top">
                        {{ DateHelper::getRelativeTime($comment->created_at) }}
                    </span>
                </div>
                @if($comment->user)
                    <span class="login pull-left">{{ $comment->user->login }}</span>
                @else
                    <span class="login pull-left">{{ $comment->user_name }}</span>
                @endif
                <p class="list-group-item-text" style="clear: both">
                    <a href="{{ URL::to($comment->getUrl()) }}">
                        {{ $comment->getCutComment() }}
                    </a>
                </p>
            </div>
        </div>
    @endforeach
</div>