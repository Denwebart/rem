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
                <h2>{{ $title }}</h2>

                @if(count($subscriptions))
                    <section id="subscriptions-area" class="blog">
                        <div class="count">
                            Показано подписок: <span>{{ $subscriptions->count() }}</span>.
                            Всего: <span>{{ $subscriptions->getTotal() }}</span>.
                        </div>

                        @foreach($subscriptions as $subscription)
                            @if($subscription->page)
                                <div data-page-id="{{ $subscription->page->id }}" class="well">
                                    <div class="row">
                                        @include('cabinet::user.pageInfo', ['page' => $subscription->page, 'item' => $subscription])

                                        <div class="col-md-12">
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
                            @else
                                <div data-page-id="{{ $subscription->page_id }}" class="well">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <h3></h3>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="buttons">
                                                <a href="javascript:void(0)" class="pull-right unsubscribe" data-id="{{ $subscription->page_id }}" title="Отписаться" data-toggle="tooltip" data-placement="top">
                                                    Отписаться
                                                </a>
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
                        @endforeach
                        <div>
                            {{ $subscriptions->links() }}
                        </div>
                    </section>
                @else
                    @if(Auth::user()->is($user))
                        <p>
                            Вы еще не подписались ни на один вопрос.
                        </p>
                    @else
                        <p>
                            Подписок нет.
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
        $(".unsubscribe").on('click', function() {
            var $link = $(this);
            var pageId = $link.data('id');
            $.ajax({
                url: "{{ URL::route('user.unsubscribe', ['login' => Auth::user()->getLoginForUrl()]) }}",
                dataType: "text json",
                type: "POST",
                data: {pageId: pageId},
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function(response) {
                    if(response.success){
                        $('[data-page-id=' + pageId + ']').remove();
                    }
                }
            });
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