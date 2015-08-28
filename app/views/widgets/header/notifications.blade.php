<li class="dropdown dropdown-notifications">
    @if(count($notifications))
        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
            <i class="material-icons">notifications</i>
            <span class="label label-warning">
                {{ $notifications->getTotal() }}
            </span>
        </a>
    @else
        <a href="{{ URL::route('user.notifications', ['login' => Auth::user()->getLoginForUrl()]) }}" title="Все уведомления" data-toggle="tooltip" data-placement="bottom">
            <i class="material-icons">notifications</i>
        </a>
    @endif
    <ul class="dropdown-menu">
        <li class="header">
            <i class="material-icons">notifications</i>
            Уведомления:
            @if(count($notifications))
                @if($notifications->count() < $notifications->getTotal())
                    <span>{{ $notifications->count() }} из {{ $notifications->getTotal() }}</span>
                @else
                    <span>{{ $notifications->count() }}</span>
                @endif
            @else
                <span>{{ count($notifications) }}</span>
            @endif
        </li>
        <li>
            <ul>
                @foreach($notifications as $notification)
                    <li data-notification-id="{{ $notification->id }}">
                        <a href="{{ URL::route('user.notifications', ['login' => Auth::user()->getLoginForUrl()]) }}#notification-{{$notification->id}}">
                            {{ Notification::$typeIcons[$notification->type] }}
                            <small>
                                {{ DateHelper::getRelativeTime($notification->created_at) }}
                            </small>
                            <p>{{ $notification->getMessageWithoutLinks() }}</p>
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>
        <li class="footer">
            <a href="{{ URL::route('user.notifications', ['login' => Auth::user()->getLoginForUrl()]) }}">
                Показать все
            </a>
        </li>
    </ul>
</li>