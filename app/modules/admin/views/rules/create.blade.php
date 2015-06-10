@extends('admin::layouts.admin')

@section('content')
    <div class="page-head">
        <h1>Создание правила <small></small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li><a href="{{ URL::route('admin.settings.index') }}">Настройки</a></li>
            <li class="active"><a href="{{ URL::route('admin.advertising.index') }}">Правила сайта</a></li>
            <li>Создание правила</li>
        </ol>
    </div>

    <div class="content label-normal">
        <div class="row">
            {{ Form::model($rule, ['method' => 'POST', 'route' => ['admin.rules.store']], ['id' => 'ruleForm']) }}
                @include('admin::rules._form')
            {{ Form::close() }}
        </div>
    </div>
@stop