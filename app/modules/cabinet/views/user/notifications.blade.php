@extends('cabinet::layouts.cabinet')

<?php
$title = (Auth::user()->is($user)) ? 'Уведомления' : 'Уведомления пользователя ' . $user->login;
View::share('title', $title);
?>

@section('content')
    <div class="col-lg-3 col-md-3">
        @include('cabinet::user.userInfo')

        {{ $areaWidget->leftSidebar() }}
    </div>
    <div class="col-lg-7 col-md-7">
        <!-- Breadcrumbs -->
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('/') }}">Главная</a></li>
            <li>
                <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                    {{ (Auth::user()->is($user)) ? 'Мой профиль' : 'Профиль пользователя ' . $user->login }}
                </a>
            </li>
            <li>{{ $title }}</li>
        </ol>

        <div class="row">
            <div class="col-lg-12" id="content">
                <h2>{{ $title }}</h2>

                @if(count($notifications))
                    <section id="notifications-area" class="blog">
                        <div class="count">
                            Показано уведомлений: <span>{{ $notifications->count() }}</span>.
                            Всего: <span>{{ $notifications->getTotal() }}</span>.
                        </div>

                        @foreach($notifications as $notification)
                            <div data-notification-id="{{ $notification->id }}" class="well">
                                <div class="row">
                                    <div class="col-md-10">
                                        <h3>
                                            {{ Notification::$typeIcons[$notification->type] }}
                                            {{ $notification->message }}
                                        </h3>
                                        <div class="date pull-left" title="Дата уведомления" data-toggle="tooltip" data-placement="top">
                                            <i class="material-icons">today</i>
                                            <span>{{ DateHelper::dateFormat($notification->created_at) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="buttons">
                                            <a href="javascript:void(0)" class="pull-right remove-notification" data-id="{{ $notification->id }}" title="Удалить уведомление" data-toggle="tooltip" data-placement="top">
                                                <i class="material-icons">close</i>
                                            </a>
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
            </div>
            <div class="col-lg-12">
                {{ $areaWidget->contentBottom() }}
            </div>
        </div>
    </div>
@stop

@section('script')
    @parent

    <script type="text/javascript">
        $(".remove-notification").on('click', function() {
            var $link = $(this);
            var notificationId = $link.data('id');
            $.ajax({
                url: "{{ URL::route('user.deleteNotification', ['login' => Auth::user()->getLoginForUrl()]) }}",
                dataType: "text json",
                type: "POST",
                data: {notificationId: notificationId},
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function(response) {
                    if(response.success){
                        if(response.newNotifications != 0) {
                            $('#header-widget .dropdown-notifications .dropdown-toggle span').text(response.newNotifications);
                            $('#header-widget .dropdown-notifications .dropdown-menu .header span').text(response.newNotifications);
                            $('#header-widget .dropdown-notifications .dropdown-menu [data-notification-id= ' + notificationId + ']').remove();
                            $('#users-menu .notifications small').text(response.newNotifications);
                        } else {
                            $('#header-widget .dropdown-notifications .dropdown-toggle span').remove();
                            $('#header-widget .dropdown-notifications .dropdown-menu').remove();
                            $('#users-menu .notifications small').remove();
                            // как ссылка
                            $('#header-widget .dropdown-notifications .dropdown-toggle').remove();
                            $('#header-widget .dropdown-notifications').prepend('<a href="<?php echo URL::route('user.notifications', ['login' => Auth::user()->getLoginForUrl()]) ?>"><i class="material-icons">notifications</i></a>');
                        }
                        $('[data-notification-id=' + notificationId + ']').remove();
                    }
                }
            });
        });
    </script>
@endsection
