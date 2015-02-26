<li class="dropdown dropdown-messages">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-send"></i><span class="label label-info">{{ count($messages) }}</span>
    </a>
    <ul class="dropdown-menu">
        <li class="header"><i class="fa fa-envelope"></i> Новые личные сообщения: {{ count($messages) }}</li>
        <li>
            <ul>
                @foreach($messages as $message)
                <li>
                    <a href="{{ URL::route('user.messages', ['login' => Auth::user()->login]) }}">
                        <div class="pull-left">
                            {{ HTML::image(Config::get('settings.defaultAvatar'), $message->userSender->login, ['class' => 'img-rounded']) }}
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
</li>