@if(count($notifications))
    <section id="notifications-area" class="blog">
        <div class="count">
            Показано уведомлений: <span class="on-page">{{ $notifications->count() }}</span>.
            Всего: <span class="total">{{ $notifications->getTotal() }}</span>.
        </div>

        @foreach($notifications as $notification)
            <div data-notification-id="{{ $notification->id }}" class="well">
                <div class="row">
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-11">
                                <div class="date pull-left" title="Дата уведомления" data-toggle="tooltip" data-placement="top">
                                    <span>{{ DateHelper::dateFormat($notification->created_at) }}</span>
                                </div>
                            </div>
                            <div class="col-md-1" style="padding-right: 0">
                                <div class="icon">
                                    {{ Notification::$typeIcons[$notification->type] }}
                                </div>
                            </div>
                            <div class="col-md-11">
                                <h3>
                                    {{ $notification->message }}
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="buttons">
                            @if(Auth::user()->is($user))
                                <a href="javascript:void(0)" class="pull-right remove-notification" data-id="{{ $notification->id }}" title="Удалить уведомление" data-toggle="tooltip" data-placement="top">
                                    <i class="material-icons">close</i>
                                </a>
                            @endif
                        </div>
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