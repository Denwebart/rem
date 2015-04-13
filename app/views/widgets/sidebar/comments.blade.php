<div id="comments-sidebar-widget" class="list-group sidebar-widget">
    <h4>Комментарии</h4>

    @foreach($comments as $comment)
        <div class="list-group-item">
            <div class="row-picture">
                <a href="{{ URL::route('user.profile', ['login' => $comment->user->getLoginForUrl()]) }}">
                    {{ $comment->user->getAvatar('mini', ['class' => 'circle']) }}
                    {{ $comment->user->login }}
                </a>
            </div>
            <div class="row-content">
                <div class="created-date pull-right">
                    {{ DateHelper::getRelativeTime($comment->created_at) }}
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