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
            @if(Auth::check())
                @if(Auth::user()->isAdmin() || Auth::user()->isModerator())
                    <a href="{{ URL::route('admin.comments.edit', ['id' => $commentLevel2->id]) }}">
                        <i class="material-icons">mode_edit</i>
                    </a>
                @endif()
            @endif()
        </h4>
        <div>
            {{ StringHelper::addFancybox($commentLevel2->comment, 'group-comment-' . $commentLevel2->id) }}
        </div>

        @include('widgets.comment.vote', ['comment' => $commentLevel2, 'isBannedIp' => $isBannedIp,])

    </div>
</div>