@extends('cabinet::layouts.honors')

<?php
$title = $honor->title;
View::share('title', $title);
?>

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('/') }}">Главная</a></li>
        <li><a href="{{ URL::route('honors') }}">Награды</a></li>
        <li>{{ $honor->title }}</li>
    </ol>

    <section id="content">

        <div class="row">
            <div class="col-md-6">
                <div class="well">
                    <h2>{{ $honor->title }}</h2>

                    {{ $honor->getImage() }}
                    <hr/>
                    {{ $honor->description }}
                </div>
            </div>
            <div class="col-md-6">
                <h3>Награжденные пользователи</h3>

                @if(count($honor->users))
                    <ul class="users">
                        @foreach($honor->users as $user)
                            <li>
                                <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                                    {{ $user->getAvatar('mini') }}
                                    {{ $user->login }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p>Еще ни у кого нет этой награды</p>
                @endif
            </div>
        </div>

    </section>
@stop
