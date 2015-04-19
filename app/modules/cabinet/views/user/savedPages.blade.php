@extends('cabinet::layouts.cabinet')

<?php
$title = (Auth::user()->is($user)) ? 'Сохранённое' : 'Сохранённое пользователем ' . $user->login;
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

            <div id="saved-pages">

                @foreach($pages as $page)

                    <div data-question-id="{{ $page->page->id }}" class="col-md-12">
                        <div class="well">
                            <div class="pull-right">
                                <a href="#" class="">
                                    <i class="glyphicon glyphicon-floppy-remove"></i>
                                </a>
                            </div>
                            <h3>
                                <a href="{{ URL::to($page->page->getUrl()) }}">
                                    {{ $page->page->title }}
                                </a>
                            </h3>
                            <div class="date date-create">{{ $page->page->created_at }}</div>

                            <div>
                                {{ $page->page->content }}
                            </div>

                        </div>
                    </div>

                @endforeach

                <div>
                    {{ $pages->links() }}
                </div>

            </div>

        </div>
    </div>
@stop