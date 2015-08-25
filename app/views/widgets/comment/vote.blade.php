<?php
$canVote = (!$isBannedIp) ? Auth::check() ? !Auth::user()->is($comment->user) ? true : false : false : false;
?>
<div class="vote pull-right" data-vote-comment-id="{{ $comment->id }}" @if(!$canVote) style="margin: 0" @endif>
    @if($canVote)
        <a href="javascript:void(0)" class="vote-like">
            <i class="material-icons">arrow_drop_up</i>
        </a>
    @endif
    <span class="vote-result">{{ $comment->votes_like - $comment->votes_dislike }}</span>
    @if($canVote)
        <a href="javascript:void(0)" class="vote-dislike">
            <i class="material-icons">arrow_drop_down</i>
        </a>
    @endif
</div>