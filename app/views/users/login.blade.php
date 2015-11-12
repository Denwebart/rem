@extends('layouts.login')

<?php
$title = 'Вход на сайт';
View::share('title', $title);
?>

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-lg-offset-4 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-8 col-xs-offset-2">
                <a href="{{ URL::to('/') }}">
                    {{ HTML::image('images/logo.png', '', ['class' => 'img-responsive margin-bottom-20']) }}
                </a>
            </div>
            <div class="clearfix"></div>
            <div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 well">
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

                    <div class="form-group @if($errors->has('login')) has-error @endif">
                        {{ Form::text('login', '', ['class' => 'form-control floating-label', 'placeholder' => 'Email или логин*', 'autofocus'=>'autofocus']); }}
                        @if($errors->has('login')) <small class="text-danger">{{ $errors->first('login') }}</small> @endif
                    </div>
                    <div class="form-group @if($errors->has('password')) has-error @endif">
                        {{ Form::password('password', ['class' => 'form-control floating-label', 'placeholder' => 'Пароль*']); }}
                        @if($errors->has('password')) <small class="text-danger">{{ $errors->first('password') }}</small> @endif
                    </div>
                    <div class="checkbox">
                        <label>
                            {{ Form::checkbox('remember', 'remember-me', ['class' => 'form-control']); }} Запомнить меня
                        </label>
                    </div>
                    <div class="form-group @if($errors->has('g-recaptcha-response')) has-error @endif">
                        {{--{{ Form::captcha() }}--}}
                        {{--@if ($errors->has('g-recaptcha-response'))--}}
                            {{--<div class="clearfix"></div>--}}
                            {{--<small class="text-danger">{{ $errors->first('g-recaptcha-response') }}</p>--}}
                        {{--@endif--}}
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-4 col-md-5 col-sm-4">
                                {{ Form::submit('Войти', ['id'=> 'submit', 'class' => 'btn btn-primary btn-full']) }}
                            </div>
                            <div class="col-lg-8 col-md-7 col-sm-8">
                                <a href="{{ URL::route('register') }}">Регистрация</a>
                                <a href="{{ URL::to('password/remind') }}">Забыли пароль?</a>
                            </div>
                        </div>
                    </div>

                    {{ Form::hidden('_token', csrf_token()) }}
                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop