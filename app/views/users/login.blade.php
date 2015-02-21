@extends('layouts.login')

@section('title')
    Вход
@stop

@section('headExtra')
    {{ HTML::style('css/signin.css') }}
@stop

@section('content')
    <div class="container">
        @if (Session::has('alert'))
            <div class="alert alert-danger">
                <p>{{ Session::get('alert') }}
            </div>
        @endif

            {{ Form::open([
                  'action' => ['UsersController@postLogin'],
                  'class' => 'form-signin',
                  ])
            }}
            <h2 class="form-signin-heading">Ваши данные</h2>

            {{ Form::text('login', '', ['class' => 'form-control', 'placeholder' => 'Email или имя*', 'required'=>'required', 'autofocus'=>'autofocus']); }}
            @if ($errors->has('login')) <p class="text-danger">{{ $errors->first('login') }}</p> @endif

            {{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Пароль*', 'required'=>'required']); }}
            @if ($errors->has('password')) <p class="text-danger">{{ $errors->first('password') }}</p> @endif

            <label class="checkbox">
                {{ Form::checkbox('remember', 'remember-me', ['class' => 'form-control']); }}
                Запомнить меня
            </label>

            {{ Form::submit('Войти', ['id'=> 'submit', 'class' => 'btn btn-lg btn-primary btn-block']) }}

            <a href="{{ URL::to('password/remind') }}">Забыли пароль?</a><br />
            <a href="{{ URL::to('users/register') }}">Регистрация</a>

            {{ Form::close() }}
    </div>
@stop