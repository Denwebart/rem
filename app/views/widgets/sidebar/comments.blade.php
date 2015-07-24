<div id="comments-sidebar-widget" class="list-group sidebar-widget">
    @foreach($comments as $key => $comment)
        @if($key != 0)
            <div class="list-group-separator"></div>
        @endif
        <div class="list-group-item">
            <div class="row-picture">
                @if($comment->user)
                    <a href="{{ URL::route('user.profile', ['login' => $comment->user->getLoginForUrl()]) }}">
                        {{ $comment->user->getAvatar('mini', ['class' => 'circle']) }}
                        {{ $comment->user->login }}
                    </a>
                @else
                    <a href="{{ URL::to($comment->getUrl()) }}">
                        {{ (new User)->getAvatar('mini', ['class' => 'circle']) }}
                        {{ $comment->user_name }}
                    </a>
                @endif
            </div>
            <div class="row-content">
                <div class="created-date pull-right">
                    <span class="relative-date" title="{{ DateHelper::dateFormat($comment->created_at) }}">
                        {{ DateHelper::getRelativeTime($comment->created_at) }}
                    </span>
                </div>
                <p class="list-group-item-text" style="clear: both">
                    <a href="{{ URL::to($comment->getUrl()) }}">
                        {{ StringHelper::limit($comment->comment, 70) }}
                    </a>
                </p>
            </div>
        </div>
    @endforeach
</div>