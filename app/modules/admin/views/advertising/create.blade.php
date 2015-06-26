@extends('admin::layouts.admin')

<?php
$title = 'Создание рекламного блока';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <h1><i class="fa fa-plus "></i>
            {{ $title }}
            <small>заполните все необходимые формы</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active"><a href="{{ URL::route('admin.advertising.index') }}">Реклама</a></li>
            <li>{{ $title }}</li>
        </ol>
    </div>

    <div class="content label-normal">
        <div class="row">
            {{ Form::model($advertising, ['method' => 'POST', 'route' => ['admin.advertising.store']], ['id' => 'advertisingForm']) }}
                @include('admin::advertising._form')
            {{ Form::close() }}
        </div>
    </div>
@stop