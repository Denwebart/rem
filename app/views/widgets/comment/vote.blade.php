<div class="vote pull-right" data-vote-comment-id="{{ $comment->id }}">
    @if(!$isBannedIp)
        @if(Auth::check())
            @if(!Auth::user()->is($comment->user))
                <a href="javascript:void(0)" class="vote-dislike">
                    <i class="material-icons">arrow_drop_down</i>
                </a>
            @endif
        @endif
    @endif
    <span class="vote-result">{{ $comment->votes_like - $comment->votes_dislike }}</span>
    @if(!$isBannedIp)
        @if(Auth::check())
            @if(!Auth::user()->is($comment->user))
                <a href="javascript:void(0)" class="vote-like">
                    <i class="material-icons">arrow_drop_up</i>
                </a>
                <div class="vote-message"></div>
            @endif
        @endif
    @endif
</div>