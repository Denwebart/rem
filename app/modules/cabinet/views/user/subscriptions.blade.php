@extends('cabinet::layouts.cabinet')

<?php
$title = (Auth::user()->is($user)) ? 'Мои подписки' : 'Подписки пользователя ' . $user->login;
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
                            @if(count($subscriptions))
                                <a href="javascript:void(0)" class="btn btn-primary btn-sm pull-right" id="unsubscribe-from-all" title="Отписаться от всего" data-toggle="tooltip">
                                    Отписаться от всего
                                </a>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="list">
                    @if(count($subscriptions))
                        <section id="subscriptions-area" class="blog">
                            <div class="count">
                                Показано подписок: <span>{{ $subscriptions->count() }}</span>.
                                Всего: <span>{{ $subscriptions->getTotal() }}</span>.
                            </div>

                            @foreach($subscriptions as $subscription)
                                @if($subscription->onPage())
                                    @if($subscription->page)
                                        <div data-subscription-object-id="{{ $subscription->page->id }}" class="well">
                                            <div class="row">
                                                @if(Page::TYPE_QUESTION == $subscription->page->type)
                                                    @include('cabinet::user.questionInfo', ['page' => $subscription->page, 'item' => $subscription])
                                                @else
                                                    @include('cabinet::user.pageInfo', ['page' => $subscription->page, 'item' => $subscription])
                                                @endif

                                                <div class="col-md-12">
                                                    @if(count($subscription->notifications))
                                                        <div class="subscription-notifications">
                                                            <div class="count">
                                                                Показано уведомлений по подписке: <span>{{ $subscription->notifications->count() }}</span>.
                                                                {{--Всего: <span>{{ $subscription->notifications->getTotal() }}</span>.--}}
                                                            </div>
                                                            @foreach($subscription->notifications as $notification)
                                                                <div class="alert alert-dismissable alert-info" data-notification-id="{{ $notification->id }}">
                                                                    <button type="button" class="close" data-dismiss="alert" data-id="{{ $notification->id }}">×</button>
                                                                    <span class="date">
                                                                        <i class="material-icons mdi-info">lens</i>
                                                                        {{ DateHelper::dateFormat($notification->created_at) }}
                                                                    </span>
                                                                    {{ $notification->message }}
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div data-subscription-object-id="{{ $subscription->page_id }}" class="well">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <h3></h3>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="buttons">
                                                        @if(Auth::user()->is($user))
                                                            <a href="javascript:void(0)" class="pull-right unsubscribe" data-subscription-field="{{ Subscription::FIELD_PAGE_ID }}" data-subscription-object-id="{{ $subscription->page_id }}" title="Отписаться" data-toggle="tooltip" data-placement="top">
                                                                Отписаться
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="date date-saved">
                                                        <span class="text">Подписка оформлена</span>
                                                        <span class="date">{{ DateHelper::dateFormat($subscription->created_at) }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <p>
                                                        Статья, на которую вы были подписаны, была удалена.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @elseif($subscription->onJournal())
                                    @if($subscription->userJournal)
                                        <div data-subscription-object-id="{{ $subscription->userJournal->id }}" class="well">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <div class="date date-saved">
                                                        <span class="text">Подписка оформлена</span>
                                                        <span class="date">{{ DateHelper::dateFormat($subscription->created_at) }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="buttons without-margin">
                                                        @if(Auth::user()->is($user))
                                                            <a href="javascript:void(0)" class="pull-right unsubscribe" data-subscription-field="{{ Subscription::FIELD_JOURNAL_ID }}" data-subscription-object-id="{{ $subscription->journal_id }}" title="Отписаться" data-toggle="tooltip" data-placement="top">
                                                                <i class="material-icons">close</i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-12 margin-top-10">
                                                    @if($subscription->userJournal->avatar)
                                                        <div class="user">
                                                            <a href="{{ URL::route('user.profile', ['login' => $subscription->userJournal->getLoginForUrl()]) }}" class="avatar-link display-inline-block">
                                                                {{ $subscription->userJournal->getAvatar('mini', ['class' => 'pull-left avatar circle']) }}
                                                                @if($subscription->userJournal->isOnline())
                                                                    <span class="is-online-status online" title="Сейчас на сайте" data-toggle="tooltip" data-placement="top"></span>
                                                                @else
                                                                    <span class="is-online-status offline" title="Офлайн. Последний раз был {{ DateHelper::getRelativeTime($subscription->userJournal->last_activity) }}" data-toggle="tooltip" data-placement="top"></span>
                                                                @endif
                                                            </a>
                                                        </div>
                                                    @endif
                                                    <h3>
                                                        <a href="{{ URL::route('user.journal', ['login' => $subscription->userJournal->getLoginForUrl()]) }}">
                                                            Бортовой журнал пользователя
                                                            {{ $subscription->userJournal->login }}
                                                            @if($subscription->userJournal->getFullName())
                                                                ({{ $subscription->userJournal->getFullName() }})
                                                            @endif
                                                        </a>
                                                    </h3>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="subscription-notifications">
                                                        @foreach($subscription->notifications as $notification)
                                                            <div class="alert alert-dismissable alert-info" data-notification-id="{{ $notification->id }}">
                                                                <button type="button" class="close" data-dismiss="alert" data-id="{{ $notification->id }}">×</button>
                                                                {{ DateHelper::dateFormat($notification->created_at) }}
                                                                <br/>
                                                                {{ $notification->message }}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div data-subscription-object-id="{{ $subscription->journal_id }}" class="well">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <h3></h3>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="buttons without-margin">
                                                        @if(Auth::user()->is($user))
                                                            <a href="javascript:void(0)" class="pull-right unsubscribe" data-subscription-field="{{ Subscription::FIELD_JOURNAL_ID }}" data-subscription-object-id="{{ $subscription->journal_id }}" title="Отписаться" data-toggle="tooltip" data-placement="top">
                                                                <i class="material-icons">close</i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="date date-saved">
                                                        <span class="text">Подписка оформлена</span>
                                                        <span class="date">{{ DateHelper::dateFormat($subscription->created_at) }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <p>
                                                        Журнал пользователя был удален.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                            <div>
                                {{ $subscriptions->links() }}
                            </div>
                        </section>
                    @else
                        @if(Auth::user()->is($user))
                            <p>
                                Вы еще не подписались ни на один вопрос или журнал пользователя.
                            </p>
                        @else
                            <p>
                                Подписок нет.
                            </p>
                        @endif
                    @endif
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

    <script type="text/javascript">
        $(".unsubscribe").on('click', function() {
            var $link = $(this);
            var subscriptionObjectId = $link.data('subscriptionObjectId');
            var subscriptionField = $link.data('subscriptionField');
            $.ajax({
                url: "{{ URL::route('user.unsubscribe', ['login' => Auth::user()->getLoginForUrl()]) }}",
                dataType: "text json",
                type: "POST",
                data: {subscriptionObjectId: subscriptionObjectId, subscriptionField: subscriptionField},
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function(response) {
                    if(response.success){
                        $('#site-messages').prepend(response.message);
                        $('[data-subscription-object-id=' + subscriptionObjectId + ']').remove();
                    } else {
                        $('#site-messages').prepend(response.message);
                    }
                }
            });
        });

        $('#unsubscribe-from-all').on('click', function(){
            var $button = $(this);
            if(confirm('Вы уверены, что хотите отписаться от всего?')) {
                $.ajax({
                    url: "{{ URL::route('user.unsubscribeFromAll', ['login' => Auth::user()->getLoginForUrl()]) }}",
                    dataType: "text json",
                    type: "POST",
                    data: {},
                    beforeSend: function (request) {
                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#site-messages').prepend(response.message);
                            $button.parent().find('.tooltip').remove();
                            $button.remove();
                            $('#content .list').html('<p>Вы еще не подписались ни на один вопрос или журнал пользователя.</p>');
                        } else {
                            $('#site-messages').prepend(response.message);
                        }
                    }
                });
            }
        });

        $(".close").on('click', function() {
            var $link = $(this);
            var notificationId = $link.data('id');
            $.ajax({
                url: "{{ URL::route('user.deleteSubscriptionNotification', ['login' => Auth::user()->getLoginForUrl()]) }}",
                dataType: "text json",
                type: "POST",
                data: {notificationId: notificationId},
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function(response) {
                    if(response.success){
                        $('[data-notification-id=' + notificationId + ']').remove();
                    }
                }
            });
        });
    </script>
@endsection