@if(count($messages))
    @foreach($messages->reverse() as $message)
        <div class="row item" data-message-id="{{ $message->id }}">
            <div class="col-lg-2 col-sm-2 hidden-md hidden-xs">
                @if($user->id == $message->userSender->id)
                    <a href="{{ URL::route('user.profile', ['login' => $message->userSender->getLoginForUrl()]) }}" class="pull-right avatar-link gray-background display-inline-block">
                        {{ $message->userSender->getAvatar('mini', ['class' => 'avatar circle', 'data-placement' => 'right']) }}
                        @if($message->userSender->isOnline())
                            <span class="is-online-status online" title="Сейчас на сайте" data-toggle="tooltip" data-placement="right"></span>
                        @else
                            <span class="is-online-status offline" title="Последний раз был {{ DateHelper::getRelativeTime($message->userSender->last_activity) }}" data-toggle="tooltip" data-placement="right"></span>
                        @endif
                    </a>
                @endif
            </div>

            @if($user->id == $message->userSender->id)
                <div class="col-lg-7 col-md-11 col-sm-7 col-xs-11">
                    <div class="message outgoing">
                        <a href="javascript:void(0)" class="delete-message pull-right" data-id="{{ $message->id }}">
                            <i class="material-icons">clear</i>
                        </a>

                        <div class="login pull-left hidden-lg hidden-sm">
                            я
                        </div>
                        <span class="date">
                            {{ DateHelper::dateForMessage($message->created_at) }}
                        </span>
                        <div class="clearfix"></div>
                        {{ StringHelper::addFancybox($message->message, 'group-message-' . $message->id) }}
                    </div>
                </div>
            @else
                <div class="col-lg-7 col-lg-offset-1 col-md-11 col-md-offset-1 col-sm-7 col-sm-offset-1 col-xs-11 col-xs-offset-1">
                    <div class="message {{ is_null($message->read_at) ? 'new-message' : ''}}" data-message-id="{{ $message->id }}">
                        <a href="javascript:void(0)" class="delete-message pull-right" data-id="{{ $message->id }}">
                            <i class="material-icons">clear</i>
                        </a>

                        <div class="login pull-left hidden-lg hidden-sm">
                            {{ $message->userSender->login }}
                        </div>
                        <span class="date">
                            {{ DateHelper::dateForMessage($message->created_at) }}
                        </span>
                        <div class="clearfix"></div>
                        {{ StringHelper::addFancybox($message->message, 'group-message-' . $message->id) }}
                    </div>
                </div>
            @endif

            <div class="col-lg-2 col-sm-2 hidden-md hidden-xs">
                @if($companion->id == $message->userSender->id)
                    <a href="{{ URL::route('user.profile', ['login' => $message->userSender->getLoginForUrl()]) }}" class="avatar-link gray-background display-inline-block">
                        {{ $message->userSender->getAvatar('mini', ['class' => 'avatar circle', 'data-placement' => 'left']) }}
                        @if($message->userSender->isOnline())
                            <span class="is-online-status online" title="Сейчас на сайте" data-toggle="tooltip" data-placement="left"></span>
                        @else
                            <span class="is-online-status offline" title="Последний раз был {{ DateHelper::getRelativeTime($message->userSender->last_activity) }}" data-toggle="tooltip" data-placement="left"></span>
                        @endif
                    </a>
                @endif
            </div>
        </div>
    @endforeach
@else
    <p class="no-messages">Сообщений нет.</p>
@endif