<div class="media" id="comment-{{ $comment->id }}" >
    <a class="pull-left" href="{{ URL::route('user.profile', ['login' => $comment->user->getLoginForUrl()]) }}">
        {{ $comment->user->getAvatar('mini', ['class' => 'media-object']) }}
    </a>
    <div class="media-body">
        <h4 class="media-heading">
            <a href="{{ URL::route('user.profile', ['login' => $comment->user->getLoginForUrl()]) }}" class="author{{ ($page->user_id == $comment->user_id) ? ' page-author' : '' }}">{{ $comment->user->login }}</a>
            <small>{{ DateHelper::dateFormat($comment->created_at) }}</small>
        </h4>
        <div>{{ $comment->comment }}</div>

        <div class="vote pull-right" data-vote-comment-id="{{ $comment->id }}">
            <a href="javascript:void(0)" class="vote-dislike"><span class="glyphicon glyphicon-triangle-bottom"></span></a>
            <span class="vote-result">{{ $comment->votes_like - $comment->votes_dislike }}</span>
            <a href="javascript:void(0)" class="vote-like"><span class="glyphicon glyphicon-triangle-top"></span></a>
            <div class="vote-message"></div>
        </div>

    </div>
</div>