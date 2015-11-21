<li class="dropdown dropdown-notifications">
    @if(count($notifications))
        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
            <i class="material-icons">notifications</i>
            <span class="label label-warning">
                {{ count($notifications) }}
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
                @if($limit < count($notifications))
                    <span>{{ $limit }} из {{ count($notifications) }}</span>
                @else
                    <span>{{ count($notifications) }}</span>
                @endif
            @else
                <span>{{ count($notifications) }}</span>
            @endif
        </li>
        <li>
            <ul>
                @foreach($notifications as $key => $notification)
                    @if($key < $limit)
                        <li data-notification-id="{{ $notification->id }}">
                            <a href="{{ URL::route('user.notifications', ['login' => Auth::user()->getLoginForUrl()]) }}#notification-{{$notification->id}}">
                                {{ Notification::$typeIcons[$notification->type] }}
                                <small>
                                    {{ DateHelper::getRelativeTime($notification->created_at) }}
                                </small>
                                <p>{{ strip_tags($notification->message) }}</p>
                            </a>
                        </li>
                    @endif
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