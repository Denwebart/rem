@extends('layouts.users')

<?php
$title = 'Все пользователи';
View::share('page', $title);
?>

@section('content')
    <section id="content">
        <h2>{{ $title }}</h2>

        <div id="users">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-4"></div>
                <div class="col-md-2">Статьи</div>
                <div class="col-md-2">Вопросы</div>
                <div class="col-md-2">Комменатрии</div>
            </div>

            @foreach($users as $user)

                <div class="row margin-bottom-20">
                    <div class="col-md-2">
                        <a href="{{ URL::route('user.profile', ['login' => $user->login]) }}">
                            {{ $user->getAvatar('mini') }}
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ URL::route('user.profile', ['login' => $user->login]) }}">
                            {{ $user->login }}
                        </a>
                        @if($user->getFullName())
                            <p>{{ $user->getFullName() }}</p>
                        @endif

                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-2"></div>
                    <div class="col-md-2">
                        <a href="{{ URL::route('user.comments', ['login' => $user->login]) }}">
                            {{ count($user->publishedСomments) }}
                        </a>
                    </div>
                </div>

            @endforeach

            {{ $users->links() }}

        </div>
    </section>
@stop