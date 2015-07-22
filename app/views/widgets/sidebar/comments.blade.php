<div id="comments-sidebar-widget" class="list-group sidebar-widget">
    @foreach($comments as $comment)
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
                    <span class="relative-date">
                        {{ DateHelper::getRelativeTime($comment->created_at) }}
                    </span>
                    <span class="full-date font-mini">
                        {{ DateHelper::dateFormat($comment->created_at) }}
                    </span>
                </div>
                <p class="list-group-item-text" style="clear: both">
                    <a href="{{ URL::to($comment->getUrl()) }}">
                        {{ $comment->comment }}
                    </a>
                </p>
            </div>
        </div>
        <div class="list-group-separator"></div>
    @endforeach
</div>