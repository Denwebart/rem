@extends('cabinet::layouts.cabinet')

<?php
$title = (Auth::user()->is($user)) ? 'Мой профиль' : 'Профиль пользователя ' . $user->login;
View::share('title', $title);
?>

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('/') }}">Главная</a></li>
                <li>{{ $title }}</li>
            </ol>
        </div>

        <div class="col-lg-3">
            <div class="avatar">
                {{ $user->getAvatar() }}
            </div>
        </div>
        <div class="col-lg-9">

            @if(Auth::user()->is($user) || Auth::user()->isAdmin())
                <a href="{{{ URL::route('user.edit', ['login' => $user->login]) }}}" class="pull-right">
                    <span class="glyphicon glyphicon-edit"></span>
                    Редактировать
                </a>
            @endif

            <h2>{{{ $user->login }}}</h2>
            @if($user->getFullName())
                <h3>{{{ $user->getFullName() }}}</h3>
            @endif

            @if($user->country)
                <p>{{{ $user->country }}}</p>
            @endif

            @if($user->city)
                <p>{{{ $user->city }}}</p>
            @endif

            @if($user->car_brand)
                <p>{{{ $user->car_brand }}}</p>
            @endif

            @if($user->profession)
                <p>{{{ $user->profession }}}</p>
            @endif

            @if($user->profession)
                <p>{{ $user->description }}</p>
            @endif

        </div>
    </div>
@stop