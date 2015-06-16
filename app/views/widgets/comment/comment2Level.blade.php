<div class="media" id="comment-{{ $comment->id }}" >
    @if($comment->user)
        <a class="pull-left" href="{{ URL::route('user.profile', ['login' => $comment->user->getLoginForUrl()]) }}">
            {{ $comment->user->getAvatar('mini', ['class' => 'media-object']) }}
        </a>
    @else
        <a class="pull-left" href="javascript:void(0)">
            {{ (new User)->getAvatar('mini', ['class' => 'media-object']) }}
        </a>
    @endif
    <div class="media-body">
        <h4 class="media-heading">
            @if($comment->user)
                <a href="{{ URL::route('user.profile', ['login' => $comment->user->getLoginForUrl()]) }}" class="author{{ ($comment->page->user_id == $comment->user_id) ? ' page-author' : '' }}">
                    {{ $comment->user->login }}
                </a>
            @else
                <a href="javascript:void(0)" class="author">
                    {{ $comment->user_name }}
                </a>
            @endif
            <small>{{ DateHelper::dateFormat($comment->created_at) }}</small>
        </h4>
        <div>{{ $comment->comment }}</div>

        <div class="vote pull-right" data-vote-comment-id="{{ $comment->id }}">
            @if(Auth::check())
                @if(!Auth::user()->is($comment->user))
                    <a href="javascript:void(0)" class="vote-dislike"><span class="glyphicon glyphicon-triangle-bottom"></span></a>
                    <span class="vote-result">{{ $comment->votes_like - $comment->votes_dislike }}</span>
                    <a href="javascript:void(0)" class="vote-like"><span class="glyphicon glyphicon-triangle-top"></span></a>
                    <div class="vote-message"></div>
                @else
                    <span class="vote-result">{{ $comment->votes_like - $comment->votes_dislike }}</span>
                @endif
            @else
                <a href="javascript:void(0)" class="vote-dislike"><span class="glyphicon glyphicon-triangle-bottom"></span></a>
                <span class="vote-result">{{ $comment->votes_like - $comment->votes_dislike }}</span>
                <a href="javascript:void(0)" class="vote-like"><span class="glyphicon glyphicon-triangle-top"></span></a>
                <div class="vote-message"></div>
            @endif
        </div>

    </div>
</div>