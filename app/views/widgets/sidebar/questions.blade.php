<div id="latest-sidebar-widget" class="list-group sidebar-widget">
    <h4>Новые вопросы</h4>

    @foreach($questions as $key => $question)
        <div class="list-group-item">
            <div class="row-picture">
                <a href="{{ URL::route('user.profile', ['login' => $question->user->getLoginForUrl()]) }}">
                    {{ $question->user->getAvatar('mini', ['class' => 'circle']) }}
                    {{ $question->user->login }}
                </a>
            </div>
            <div class="row-content">
                <div class="created-date pull-right">
                    {{ DateHelper::getRelativeTime($question->published_at) }}
                    <br/>
                    <span class="font-mini">{{ DateHelper::dateFormat($question->published_at) }}</span>
                </div>
                <p class="list-group-item-text" style="clear: both">
                    <a href="{{ URL::to($question->getUrl()) }}">
                        {{ $question->getTitle() }}
                    </a>
                </p>
                <p>
                    Ответов:
                    @if(count($question->bestComments))
                        <i class="mdi-action-done mdi-success" title="Есть решение" style="font-size: 20pt;"></i>
                    @endif
                    <a href="{{ URL::to($question->getUrl()) }}#answers">
                        {{ count($question->publishedComments) }}
                    </a>
                </p>
            </div>
        </div>
        <div class="list-group-separator"></div>
    @endforeach

</div>
