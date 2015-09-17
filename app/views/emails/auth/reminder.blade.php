@extends('layouts.main')

@section('content')
	<h2>Восстановление пароля</h2>

	<div>
		Для того, чтобы поменять пароль, перейдите по ссылке: {{ URL::to('password/reset', array($token)) }}.<br/>
		Это ссылка истекает через {{ Config::get('auth.reminder.expire', 60) }} минут.
	</div>
@stop