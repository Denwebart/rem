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
                <div class="row">
                    <div class="col-md-8">
                        <h2>{{ $title }}</h2>
                    </div>
                    <div class="col-md-4">
                        @if(Auth::user()->is($user))
                            @if(count($notifications))
                                <a href="javascript:void(0)" class="btn btn-primary btn-sm pull-right" id="delete-all-notifications" title="Удалить все уведомления" data-toggle="tooltip">
                                    Удалить все
                                </a>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Список уведомлений -->
                <div class="list">
                    @include('cabinet::user.notificationsList')
                </div>
            </div>
            <div class="col-lg-12">
                {{ $areaWidget->contentBottom() }}
            </div>
        </div>
    </div>
@stop

@section('script')
    @parent

    @if(Auth::user()->is($user))
        <script type="text/javascript">
            $(document).ready(function() {
                $(".list").on('click', '.remove-notification', function() {
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
                                $('#content .list').html(response.notificationsList);
                                if(response.newNotifications != 0) {
                                    $('#header-widget .dropdown-notifications .dropdown-toggle span').text(response.newNotifications);
                                    $('#header-widget .dropdown-notifications .dropdown-menu .header span').text(response.newNotifications);
                                    $('#header-widget .dropdown-notifications .dropdown-menu [data-notification-id= ' + notificationId + ']').remove();
                                    $('#users-menu .notifications small').text(response.newNotifications);
                                } else {
                                    $('#delete-all-notifications').remove();
                                    $('#header-widget .dropdown-notifications .dropdown-toggle span').remove();
                                    $('#header-widget .dropdown-notifications .dropdown-menu').remove();
                                    $('#users-menu .notifications small').remove();
                                    // как ссылка
                                    $('#header-widget .dropdown-notifications .dropdown-toggle').remove();
                                    $('#header-widget .dropdown-notifications').prepend('<a href="<?php echo URL::route('user.notifications', ['login' => Auth::user()->getLoginForUrl()]) ?>"><i class="material-icons">notifications</i></a>');
                                }
                            }
                        }
                    });
                });

                $('#delete-all-notifications').on('click', function(){
                    if(confirm('Вы уверены, что хотите удалить все уведомления?')) {
                        $.ajax({
                            url: "{{ URL::route('user.deleteAllNotifications', ['login' => Auth::user()->getLoginForUrl()]) }}",
                            dataType: "text json",
                            type: "POST",
                            data: {},
                            beforeSend: function (request) {
                                return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                            },
                            success: function (response) {
                                if (response.success) {
                                    $('#site-messages').prepend(response.message);
                                    $('#delete-all-notifications').remove();
                                    $('#content .list').html('<p>У вас нет уведомлений.</p>');

                                    $('#header-widget .dropdown-notifications .dropdown-toggle span').remove();
                                    $('#header-widget .dropdown-notifications .dropdown-menu').remove();
                                    $('#users-menu .notifications small').remove();
                                    // как ссылка
                                    $('#header-widget .dropdown-notifications .dropdown-toggle').remove();
                                    $('#header-widget .dropdown-notifications').prepend('<a href="<?php echo URL::route('user.notifications', ['login' => Auth::user()->getLoginForUrl()]) ?>"><i class="material-icons">notifications</i></a>');
                                } else {
                                    $('#site-messages').prepend(response.message);
                                }
                            }
                        });
                    }
                });
            });
        </script>
    @endif
@endsection