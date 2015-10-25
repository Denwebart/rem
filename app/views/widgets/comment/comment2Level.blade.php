<div id="comment-{{ $commentLevel2->id }}" class="comment" itemprop="comment" itemscope itemtype="http://schema.org/Comment">
    @if(!$commentLevel2->is_deleted)
        <div class="comment-text @if($commentLevel2->mark == Comment::MARK_BEST) best @endif">
            <div class="row">
                <div class="col-md-11 col-sm-11 col-xs-10">
                    @if($commentLevel2->user)
                        <a class="pull-left avatar-link gray-background" href="{{ URL::route('user.profile', ['login' => $commentLevel2->user->getLoginForUrl()]) }}">
                            {{ $commentLevel2->user->getAvatar('mini', ['class' => 'media-object avatar circle']) }}
                            @if($commentLevel2->user->isOnline())
                                <span class="is-online-status online" title="Сейчас на сайте" data-toggle="tooltip" data-placement="top"></span>
                            @else
                                <span class="is-online-status offline" title="Последний раз был {{ DateHelper::getRelativeTime($commentLevel2->user->last_activity) }}" data-toggle="tooltip" data-placement="top"></span>
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
                                        <div itemprop="author" itemscope itemtype="http://schema.org/Person" class="display-inline-block pull-left">
                                            @if($commentLevel2->user)
                                                <a href="{{ URL::route('user.profile', ['login' => $commentLevel2->user->getLoginForUrl()]) }}" class="login {{ ($page->user_id == $commentLevel2->user_id) ? ' page-author' : '' }}" itemprop="name url">
                                                    {{ $commentLevel2->user->login }}
                                                </a>
                                            @else
                                                <a href="javascript:void(0)" class="login" itemprop="name">
                                                    {{ $commentLevel2->user_name }}
                                                </a>
                                            @endif
                                        </div>
                                        <time class="date" datetime="{{ DateHelper::dateFormatForSchema($commentLevel2->created_at) }}" itemprop="dateCreated">
                                            {{ DateHelper::dateFormat($commentLevel2->created_at) }}
                                        </time>
                                        <a href="{{ URL::to($page->getUrl()) }}#comment-{{ $commentLevel2->id }}" class="get-link pull-left margin-top-10" data-comment-id="{{ $commentLevel2->id }}" title="Ссылка на комментарий" data-toggle="tooltip" data-placement="bottom" itemprop="url">
                                            <span>#</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="comment-content" data-parent-id="{{ $commentLevel2->parent_id }}" itemprop="text">
                            {{ StringHelper::addFancybox($commentLevel2->comment, 'group-comment-' . $commentLevel2->id) }}
                        </div>
                    </div>
                </div>
                <div class="col-md-1 col-sm-1 col-xs-2">
                    @include('widgets.comment.vote', ['isBannedIp' => $isBannedIp, 'comment' => $commentLevel2])
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @if(Auth::check())
                        @if(Auth::user()->isAdmin() || Auth::user()->isModerator())
                            <div class="buttons pull-right">
                                <a href="{{ URL::route('admin.comments.edit', ['id' => $commentLevel2->id, 'backUrl' => urlencode(Request::url())]) }}" class="margin-left-20" title="Редактировать комментарий" data-toggle="tooltip" data-placement="top">
                                    <i class="material-icons">edit</i>
                                </a>
                                <a href="javascript:void(0)" class="margin-left-10 pull-right delete-comment" title="Удалить комментарий" data-id="{{ $commentLevel2->id }}" data-toggle="tooltip" data-placement="top">
                                    <i class="material-icons">delete</i>
                                </a>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="comment-text @if($commentLevel2->mark == Comment::MARK_BEST) best @endif @if($commentLevel2->is_deleted) deleted @endif">
            Комментарий удален.
        </div>
    @endif
</div>