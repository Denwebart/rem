<div class="vote pull-right" data-vote-comment-id="{{ $comment->id }}">
    @if(!$isBannedIp)
        @if(Auth::check())
            @if(!Auth::user()->is($comment->user))
                <a href="javascript:void(0)" class="vote-dislike"><span class="glyphicon glyphicon-triangle-bottom"></span></a>
            @endif
        @endif
    @endif
    <span class="vote-result">{{ $comment->votes_like - $comment->votes_dislike }}</span>
    @if(!$isBannedIp)
        @if(Auth::check())
            @if(!Auth::user()->is($comment->user))
                <a href="javascript:void(0)" class="vote-like"><span class="glyphicon glyphicon-triangle-top"></span></a>
                <div class="vote-message"></div>
            @endif
        @endif
    @endif
</div>