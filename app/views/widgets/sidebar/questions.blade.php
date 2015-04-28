<div id="latest-sidebar-widget" class="list-group sidebar-widget">
    <h4>Новые вопросы</h4>

    @foreach($questions as $question)
        <div class="list-group-item">
            <div class="row-picture">
                <a href="{{ URL::route('user.profile', ['login' => $question->user->getLoginForUrl()]) }}">
                    {{ $question->user->getAvatar('mini', ['class' => 'circle']) }}
                    {{ $question->user->login }}
                </a>
            </div>
            <div class="row-content">
                <div class="created-date pull-right">
                    {{ DateHelper::getRelativeTime($question->created_at) }}
                </div>
                <p class="list-group-item-text" style="clear: both">
                    <a href="{{ URL::to($question->getUrl()) }}">
                        {{ $question->getTitle() }}
                    </a>
                </p>
            </div>
        </div>
        <div class="list-group-separator"></div>

    @endforeach

</div>