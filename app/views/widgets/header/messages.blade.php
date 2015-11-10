<li class="dropdown dropdown-messages">
    @if(count($messages))
        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
            <i class="material-icons">send</i>
            <span class="label label-info">
                {{ $messages->getTotal() }}
            </span>
        </a>
    @else
        <a href="{{ URL::route('user.messages', ['login' => Auth::user()->getLoginForUrl()]) }}" title="Все личные сообщения" data-toggle="tooltip" data-placement="bottom">
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
                    <li data-message-id="{{ $message->id }}" data-sender-id="{{ $message->user_id_sender }}">
                        <a href="{{ URL::route('user.dialog', ['login' => Auth::user()->getLoginForUrl(), 'companion' => $message->userSender->getLoginForUrl()]) }}">
                            <div class="pull-left avatar-link">
                                {{ $message->userSender->getAvatar('mini', ['class' => 'avatar circle']) }}
                                @if($message->userSender->isOnline())
                                    <span class="is-online-status online"></span>
                                @else
                                    <span class="is-online-status offline"></span>
                                @endif
                            </div>
                            <h4>{{ $message->userSender->login }}
                                <small>
                                    {{ DateHelper::getRelativeTime($message->created_at) }}
                                </small>
                            </h4>
                            <p>{{ StringHelper::limit(strip_tags($message->message), 80) }}</p>
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