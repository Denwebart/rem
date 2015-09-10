@if(count($notifications))
    <section id="notifications-area" class="blog">
        <div class="count">
            Показано уведомлений: <span class="on-page">{{ $notifications->count() }}</span>.
            Всего: <span class="total">{{ $notifications->getTotal() }}</span>.
        </div>

        @foreach($notifications as $notification)
            <div id="notification-{{ $notification->id }}" data-notification-id="{{ $notification->id }}" class="well item">
                <div class="row">
                    <div class="col-md-1 col-sm-1 hidden-xs">
                    </div>
                    <div class="col-md-8 col-sm-8 col-xs-9">
                        <div class="date pull-left" title="Дата уведомления" data-toggle="tooltip" data-placement="top">
                            <span>{{ DateHelper::dateFormat($notification->created_at) }}</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-3">
                        <div class="buttons pull-right">
                            @if(Auth::user()->is($user))
                                <a href="javascript:void(0)" class="pull-right remove-notification" data-id="{{ $notification->id }}" title="Удалить уведомление" data-toggle="tooltip" data-placement="top">
                                    <i class="material-icons">close</i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1 col-sm-1 col-xs-1">
                        <div class="icon">
                            {{ Notification::$typeIcons[$notification->type] }}
                        </div>
                    </div>
                    <div class="col-md-11 col-sm-11 col-xs-11">
                        <p class="text">
                            {{ $notification->message }}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach

        <div>
            {{ $notifications->links() }}
        </div>
    </section>
@else
    @if(Auth::user()->is($user))
        <p>
            У вас нет уведомлений.
        </p>
    @else
        <p>
            Уведомлений нет.
        </p>
    @endif
@endif