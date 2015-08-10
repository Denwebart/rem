<li class="dropdown dropdown-messages">
    @if(count($messages))
        <a href="" class="dropdown-toggle" data-toggle="dropdown">
            <i class="material-icons">send</i>
            <span class="label label-info">
                {{ $messages->getTotal() }}
            </span>
        </a>
    @else
        <a href="{{ URL::route('user.messages', ['login' => Auth::user()->getLoginForUrl()]) }}">
            <i class="material-icons">send</i>
        </a>
    @endif
    @if(count($messages))
        <ul class="dropdown-menu">
            <li class="header">
                <i class="material-icons">send</i>
                Новые личные сообщения:
                @if($messages->count() < $messages->getTotal())
                    <span>{{ $messages->count() }} из {{ $messages->getTotal() }}</span>
                @else
                    <span>{{ $messages->count() }}</span>
                @endif
            </li>
            <li>
                <ul>
                    @foreach($messages as $message)
                    <li data-message-id="{{ $message->id }}">
                        <a href="{{ URL::route('user.dialog', ['login' => Auth::user()->getLoginForUrl(), 'companion' => $message->userSender->getLoginForUrl()]) }}">
                            <div class="pull-left">
                                {{ $message->userSender->getAvatar('mini', ['class' => 'img-rounded']) }}
                            </div>
                            <h4>{{ $message->userSender->login }}
                                <small>
                                    <i class="material-icons">access_time</i>
                                    {{ DateHelper::getRelativeTime($message->created_at) }}
                                </small>
                            </h4>
                            <p>{{ $message->message }}</p>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </li>
            <li class="footer">
                <a href="{{ URL::route('user.messages', ['login' => Auth::user()->getLoginForUrl()]) }}">
                    Показать все
                </a>
            </li>
        </ul>
    @endif
</li>