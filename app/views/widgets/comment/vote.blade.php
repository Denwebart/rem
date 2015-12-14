<?php
$canVote = (!$isBannedIp) ? (Auth::check() ? (!Auth::user()->is($comment->user) ? true : false) : true) : false;
?>
<div class="vote pull-right" data-vote-comment-id="{{ $comment->id }}" @if(!$canVote) style="margin: 0" @endif>
    @if($canVote)
        <a href="javascript:void(0)" rel="nofollow" class="vote-like" title="Нравится" data-toggle="tooltip" data-placement="top">
            <i class="material-icons">arrow_drop_up</i>
        </a>
    @endif
    <span class="vote-result" title="Рейтинг комментария" data-toggle="tooltip" data-placement="left">
        <meta itemprop="downvoteCount" content="{{ $comment->votes_dislike }}">
        <meta itemprop="upvoteCount" content="{{ $comment->votes_like }}">
        {{ $comment->votes_like - $comment->votes_dislike }}
    </span>
    @if($canVote)
        <a href="javascript:void(0)" rel="nofollow" class="vote-dislike" title="Не нравится" data-toggle="tooltip" data-placement="bottom">
            <i class="material-icons">arrow_drop_down</i>
        </a>
    @endif
</div>