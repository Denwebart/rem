<div id="questions-sidebar-widget" class="list-group sidebar-widget">
    @foreach($questions as $key => $question)
        @if($key != 0)
            <div class="list-group-separator"></div>
        @endif
        <div class="list-group-item">
            <div class="row-picture">
                <a href="{{ URL::route('user.profile', ['login' => $question->user->getLoginForUrl()]) }}">
                    {{ $question->user->getAvatar('mini', ['class' => 'circle']) }}
                    {{ $question->user->login }}
                </a>
            </div>
            <div class="row-content">
                <div class="created-date pull-right">
                    <span class="relative-date" title="{{ DateHelper::dateFormat($question->published_at) }}">
                        {{ DateHelper::getRelativeTime($question->published_at) }}
                    </span>
                </div>
                <p class="list-group-item-text row" style="clear: both">
                    <div class="col-md-10" style="padding: 0">
                        <a href="{{ URL::to($question->getUrl()) }}">
                            {{ $question->getTitle() }}
                        </a>
                    </div>
                    <div class="col-md-2" style="padding: 0">
                        @if(count($question->bestComments))
                            <i class="mdi-action-done mdi-success" title="Есть решение" style="font-size: 50px; line-height: 40px"></i>
                        @endif
                    </div>
                </p>
                <div class="clearfix"></div>
                <div class="answers pull-right">
                    <span class="icon mdi-communication-forum pull-left" title="Количество ответов"></span>
                    <a href="{{ URL::to($question->getUrl()) }}#answers" class="count pull-left @if(count($question->bestComments)) best @endif">
                        {{ count($question->publishedAnswers) }}
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>
