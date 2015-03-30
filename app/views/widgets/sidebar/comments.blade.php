<div id="comments-sidebar-widget" class="sidebar-widget">
    <h4>Комментарии</h4>

    @foreach($comments as $comment)
        <div class="item">
            <a href="{{ URL::route('user.profile', ['login' => $comment->user->getLoginForUrl()]) }}">
                {{ $comment->user->getAvatar('mini') }}
                {{ $comment->user->login }}
            </a>
            <div class="created-date">{{ DateHelper::dateFormat($comment->created_at) }}</div>
            <a href="{{ URL::to($comment->getUrl()) }}">
                {{ $comment->comment }}
            </a>
        </div>
    @endforeach

</div>