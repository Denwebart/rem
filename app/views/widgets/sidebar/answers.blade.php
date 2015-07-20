<div id="answers-sidebar-widget" class="list-group sidebar-widget">
    @foreach($answers as $answer)
        <div class="list-group-item">
            <div class="row-picture">
                @if($answer->user)
                    <a href="{{ URL::route('user.profile', ['login' => $answer->user->getLoginForUrl()]) }}">
                        {{ $answer->user->getAvatar('mini', ['class' => 'circle']) }}
                        {{ $answer->user->login }}
                    </a>
                @else
                    <a href="{{ URL::to($answer->getUrl()) }}">
                        {{ (new User)->getAvatar('mini', ['class' => 'circle']) }}
                        {{ $answer->user_name }}
                    </a>
                @endif
            </div>
            <div class="row-content">
                <div class="created-date pull-right">
                    {{ DateHelper::getRelativeTime($answer->created_at) }}
                    <br/>
                    <span class="font-mini">{{ DateHelper::dateFormat($answer->created_at) }}</span>
                </div>
                <p class="list-group-item-text" style="clear: both">
                    <a href="{{ URL::to($answer->getUrl()) }}">
                        {{ $answer->comment }}
                    </a>
                </p>
            </div>
        </div>
        <div class="list-group-separator"></div>
    @endforeach
</div>