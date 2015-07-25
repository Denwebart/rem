@extends('admin::layouts.admin')

<?php
$title = 'Создание награды';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <h1>
            <i class="fa fa-plus "></i>
            {{ $title }}
            <small>создание новой награды</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active"><a href="{{ URL::route('admin.honors.index') }}">Награды</a></li>
            <li>{{ $title }}</li>
        </ol>
    </div>

    <div class="content label-normal">
        <div class="row">
            {{ Form::model($honor, ['method' => 'POST', 'route' => ['admin.honors.store'], 'id' => 'honorsForm', 'files' => true]) }}
                @include('admin::honors._form')
                {{ Form::hidden('_token', csrf_token()) }}
            {{ Form::close() }}
        </div>
    </div>
@stop