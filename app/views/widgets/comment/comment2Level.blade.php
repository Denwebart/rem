<div id="comment-{{ $commentLevel2->id }}" class="comment">
    <div class="row">
        <div class="col-md-11">
            @if($commentLevel2->user)
                <a class="pull-left avatar-link gray-background" href="{{ URL::route('user.profile', ['login' => $commentLevel2->user->getLoginForUrl()]) }}">
                    {{ $commentLevel2->user->getAvatar('mini', ['class' => 'media-object avatar circle']) }}
                    @if($commentLevel2->user->isOnline())
                        <span class="is-online-status online" title="Сейчас на сайте" data-toggle="tooltip" data-placement="top"></span>
                    @else
                        <span class="is-online-status offline" title="Офлайн. Последний раз был {{ DateHelper::getRelativeTime($commentLevel2->user->last_activity) }}" data-toggle="tooltip" data-placement="top"></span>
                    @endif
                </a>
            @else
                <a class="pull-left avatar-link" href="javascript:void(0)">
                    {{ (new User)->getAvatar('mini', ['class' => 'media-object avatar circle']) }}
                </a>
            @endif
            <div class="media-body">
                <div class="media-heading author">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left">
                                @if($commentLevel2->user)
                                    <a href="{{ URL::route('user.profile', ['login' => $commentLevel2->user->getLoginForUrl()]) }}" class="login {{ ($page->user_id == $commentLevel2->user_id) ? ' page-author' : '' }}">
                                        {{ $commentLevel2->user->login }}
                                    </a>
                                @else
                                    <a href="javascript:void(0)" class="login">
                                        {{ $commentLevel2->user_name }}
                                    </a>
                                @endif
                                <span class="date">
                                    {{ DateHelper::dateFormat($commentLevel2->created_at) }}
                                </span>
                                @if(Auth::check())
                                    @if(Auth::user()->isAdmin() || Auth::user()->isModerator())
                                        <a href="{{ URL::route('admin.comments.edit', ['id' => $commentLevel2->id]) }}" class="margin-left-20">
                                            <i class="material-icons">edit</i>
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    {{ StringHelper::addFancybox($commentLevel2->comment, 'group-comment-' . $commentLevel2->id) }}
                </div>
            </div>
        </div>
        <div class="col-md-1">
            @include('widgets.comment.vote', ['isBannedIp' => $isBannedIp, 'comment' => $commentLevel2])
        </div>
    </div>
</div>