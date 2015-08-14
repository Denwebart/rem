@extends('layouts.login')

<?php
$title = 'Вход на сайт';
View::share('title', $title);
?>

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4 well">
                @if (Session::has('alert'))
                    <div class="alert alert-danger">
                        <p>{{ Session::get('alert') }}</p>
                    </div>
                @endif

                @if(Session::has('message'))
                    <div class="alert alert-danger">
                        <p>{{ Session::get('message') }}</p>
                    </div>
                @endif

                {{ Form::open([
                      'action' => ['UsersController@postLogin'],
                      'class' => '',
                      ])
                }}
                    <h2 class="form-signin-heading">Вход</h2>

                    <div class="form-group">
                        {{ Form::text('login', '', ['class' => 'form-control floating-label', 'placeholder' => 'Email или логин*', 'autofocus'=>'autofocus']); }}
                        @if ($errors->has('login')) <p class="text-danger">{{ $errors->first('login') }}</p> @endif
                    </div>
                    <div class="form-group">
                        {{ Form::password('password', ['class' => 'form-control floating-label', 'placeholder' => 'Пароль*']); }}
                        @if ($errors->has('password')) <p class="text-danger">{{ $errors->first('password') }}</p> @endif
                    </div>
                    <div class="checkbox">
                        <label>
                            {{ Form::checkbox('remember', 'remember-me', ['class' => 'form-control']); }} Запомнить меня
                        </label>
                    </div>
                    <div class="form-group">
                        {{ Form::captcha() }}
                        @if ($errors->has('g-recaptcha-response'))
                            <p class="text-danger">{{ $errors->first('g-recaptcha-response') }}</p>
                        @endif
                    </div>

                    <div class="form-group">
                        {{ Form::submit('Войти', ['id'=> 'submit', 'class' => 'btn btn-primary']) }}
                        <a href="{{ URL::route('register') }}">Регистрация</a>
                        <a href="{{ URL::to('password/remind') }}">Забыли пароль?</a>
                    </div>

                    {{ Form::hidden('_token', csrf_token()) }}
                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop