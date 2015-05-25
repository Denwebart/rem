@extends('cabinet::layouts.cabinet')

<?php
$title = (Auth::user()->is($user)) ? 'Мои подписки' : 'Подписки пользователя ' . $user->login;
View::share('title', $title);
?>

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('/') }}">Главная</a></li>
                <li>
                    <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                        {{ (Auth::user()->is($user)) ? 'Мой профиль' : 'Профиль пользователя ' . $user->login }}
                    </a>
                </li>
                <li>{{ $title }}</li>
            </ol>
        </div>

        <div class="col-lg-3">
            <div class="avatar">
                {{ $user->getAvatar() }}
            </div>
        </div>
        <div class="col-lg-9">
            <h2>{{ $title }}</h2>

            <div id="subscriptions">
                @foreach($subscriptions as $subscription)
                    @if($subscription->page)
                        <div data-page-id="{{ $subscription->page->id }}" class="col-md-12">
                            <div class="well">
                                <div class="pull-right">
                                    <a href="javascript:void(0)" class="remove-page" data-id="{{ $subscription->page->id }}">
                                        <i class="glyphicon glyphicon-floppy-remove"></i>
                                    </a>
                                </div>
                                <h3>
                                    <a href="{{ URL::to($subscription->page->getUrl()) }}">
                                        {{ $subscription->page->getTitle() }}
                                    </a>
                                </h3>
                                <div class="date date-create">
                                    <i>
                                        Добавлена в подписки {{ DateHelper::dateFormat($subscription->created_at) }}
                                    </i>
                                </div>
                                <div>
                                    {{ $subscription->page->getIntrotext() }}
                                </div>

                                @foreach($subscription->notifications as $notification)
                                    <div class="alert alert-dismissable alert-info">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        {{ DateHelper::dateFormat($notification->created_at) }}
                                        <br/>
                                        {{ $notification->message }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div data-page-id="{{ $subscription->page_id }}" class="col-md-12">
                            <div class="well">
                                <div class="pull-right">
                                    <a href="javascript:void(0)" id="remove-page" data-id="{{ $subscription->page_id }}">
                                        <i class="glyphicon glyphicon-floppy-remove"></i>
                                    </a>
                                </div>
                                <div class="date date-create">
                                    <i>
                                        Добавлена {{ DateHelper::dateFormat($subscription->created_at) }}
                                    </i>
                                </div>
                                <div>
                                    Статья, на которую вы были подписаны, была удалена.
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

                <div>
                    {{ $subscriptions->links() }}
                </div>

            </div>

        </div>
    </div>
@stop