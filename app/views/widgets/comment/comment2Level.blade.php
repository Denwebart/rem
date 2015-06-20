<div class="media" id="comment-{{ $commentLevel2->id }}" >
    @if($commentLevel2->user)
        <a class="pull-left" href="{{ URL::route('user.profile', ['login' => $commentLevel2->user->getLoginForUrl()]) }}">
            {{ $commentLevel2->user->getAvatar('mini', ['class' => 'media-object']) }}
        </a>
    @else
        <a class="pull-left" href="javascript:void(0)">
            {{ (new User)->getAvatar('mini', ['class' => 'media-object']) }}
        </a>
    @endif
    <div class="media-body">
        <h4 class="media-heading">
            @if($commentLevel2->user)
                <a href="{{ URL::route('user.profile', ['login' => $commentLevel2->user->getLoginForUrl()]) }}" class="author{{ ($page->user_id == $commentLevel2->user_id) ? ' page-author' : '' }}">
                    {{ $commentLevel2->user->login }}
                </a>
            @else
                <a href="javascript:void(0)" class="author">
                    {{ $commentLevel2->user_name }}
                </a>
            @endif
            <small>{{ DateHelper::dateFormat($commentLevel2->created_at) }}</small>
        </h4>
        <div>{{ $commentLevel2->comment }}</div>

        <div class="vote pull-right" data-vote-comment-id="{{ $commentLevel2->id }}">
            @if(Auth::check())
                @if(!Auth::user()->is($commentLevel2->user))
                    <a href="javascript:void(0)" class="vote-dislike"><span class="glyphicon glyphicon-triangle-bottom"></span></a>
                    <span class="vote-result">{{ $commentLevel2->votes_like - $commentLevel2->votes_dislike }}</span>
                    <a href="javascript:void(0)" class="vote-like"><span class="glyphicon glyphicon-triangle-top"></span></a>
                    <div class="vote-message"></div>
                @else
                    <span class="vote-result">{{ $commentLevel2->votes_like - $commentLevel2->votes_dislike }}</span>
                @endif
            @else
                <a href="javascript:void(0)" class="vote-dislike"><span class="glyphicon glyphicon-triangle-bottom"></span></a>
                <span class="vote-result">{{ $commentLevel2->votes_like - $commentLevel2->votes_dislike }}</span>
                <a href="javascript:void(0)" class="vote-like"><span class="glyphicon glyphicon-triangle-top"></span></a>
                <div class="vote-message"></div>
            @endif
        </div>

    </div>
</div>