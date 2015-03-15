<li class="dropdown dropdown-messages">
    @if(count($messages))
        <a href="" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-send"></i>
            <span class="label label-info">
                {{ count($messages) }}
            </span>
        </a>
    @else
        <a href="{{ URL::route('user.messages', ['login' => Auth::user()->login]) }}">
            <i class="fa fa-send"></i>
        </a>
    @endif
    @if(count($messages))
        <ul class="dropdown-menu">
            <li class="header"><i class="fa fa-envelope"></i> Новые личные сообщения: <span>{{ count($messages) }}</span></li>
            <li>
                <ul>
                    @foreach($messages as $message)
                    <li data-message-id="{{ $message->id }}">
                        <a href="{{ URL::route('user.dialog', ['login' => Auth::user()->login, 'companion' => $message->userSender->login]) }}">
                            <div class="pull-left">
                                {{ $message->userSender->getAvatar('mini', ['class' => 'img-rounded']) }}
                            </div>
                            <h4>{{ $message->userSender->login }}
                                <small>
                                    <i class="fa fa-clock-o"></i>
                                    {{ DateHelper::getRelativeTime($message->created_at) }}
                                </small>
                            </h4>
                            <p>{{ $message->message }}</p>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </li>
            <li class="footer"><a href="{{ URL::route('user.messages', ['login' => Auth::user()->login]) }}">Показать все сообщения</a></li>
        </ul>
    @endif
</li>