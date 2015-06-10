@extends('cabinet::layouts.cabinet')

<?php
$title = 'Соглашение с правилами сайта';
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
            @if(Auth::check())
                @if(!Auth::user()->is($user))
                    <a href="{{ URL::route('user.dialog', ['login' => Auth::user()->getLoginForUrl(), 'companion' => $user->getLoginForUrl()]) }}" class="btn btn-primary">
                        Написать личное сообщение
                    </a>
                @endif
            @endif
        </div>
        <div class="col-lg-9 well">
            <h2>{{ $title }}</h2>

            @if(count($rules))
                <div id="rules">
                    @foreach($rules as $rule)
                        <div>
                            <h3>{{ $rule->title }}</h3>
                            {{ $rule->description }}
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
@stop