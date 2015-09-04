<div id="questions-sidebar-widget" class="list-group sidebar-widget">
    @foreach($questions as $key => $question)
        @if($key != 0)
            <div class="list-group-separator"></div>
        @endif
        <div class="list-group-item">
            <div class="row-picture">
                <a href="{{ URL::route('user.profile', ['login' => $question->user->getLoginForUrl()]) }}" class="avatar-link gray-background">
                    {{ $question->user->getAvatar('mini', ['class' => 'avatar circle']) }}
                    @if($question->user->isOnline())
                        <span class="is-online-status online" title="Сейчас на сайте" data-toggle="tooltip" data-placement="top"></span>
                    @else
                        <span class="is-online-status offline" title="Офлайн. Последний раз был {{ DateHelper::getRelativeTime($question->user->last_activity) }}" data-toggle="tooltip" data-placement="top"></span>
                    @endif
                </a>
            </div>
            <div class="row-content">
                <div class="created-date pull-right">
                    <span class="relative-date" title="{{ DateHelper::dateFormat($question->published_at) }}">
                        {{ DateHelper::getRelativeTime($question->published_at) }}
                    </span>
                </div>
                <span class="login pull-left">{{ $question->user->login }}</span>
                <p class="list-group-item-text row" style="clear: both">
                    <div class="col-md-10" style="padding: 0">
                        <a href="{{ URL::to($question->getUrl()) }}">
                            {{ $question->getTitle() }}
                        </a>
                    </div>
                    <div class="col-md-2" style="padding: 0; display: inline-block;">
                        @if(count($question->bestComments))
                            <a href="{{ URL::to($question->getUrl()) }}#best-comments" class="icon pull-left best">
                                <i class="material-icons mdi-success" title="Есть решение">done</i>
                            </a>
                        @endif
                    </div>
                </p>
                <div class="clearfix"></div>
                <div class="answers pull-right">
                    <a href="{{ URL::to($question->getUrl()) }}#answers" class="icon pull-left @if(count($question->bestComments)) best @endif">
                        <i class="material-icons pull-left" title="Количество ответов">chat_bubble</i>
                    </a>
                    <a href="{{ URL::to($question->getUrl()) }}#answers" class="count pull-left @if(count($question->bestComments)) best @endif">
                        {{ count($question->publishedAnswers) }}
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>
