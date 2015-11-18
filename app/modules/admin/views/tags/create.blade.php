@extends('admin::layouts.admin')

<?php
$title = 'Добавление тега';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <h1>
            <i class="fa fa-plus"></i>
            {{ $title }}
        </h1>
        <ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active"><a href="{{ URL::route('admin.tags.index') }}">Теги</a></li>
            <li>{{ $tag->title }}</li>
        </ol>
    </div>
    <div class="content label-normal">
        <div class="row">
            <div class="col-md-8"></div>
            <div class="col-md-4">
                <div class="buttons margin-bottom-10 margin-top-10 display-inline-block pull-right">
                    <a href="javascript:void(0)" class="btn btn-success save-button">Сохранить</a>
                    <a href="{{ $backUrl }}" class="btn btn-primary">Отмена</a>
                </div>
            </div>
            {{ Form::model($tag, ['method' => 'POST', 'route' => ['admin.tags.store'], 'id' => 'tagsForm', 'files' => true]) }}
                @include('admin::tags._form')
                {{ Form::hidden('_token', csrf_token()) }}
            {{ Form::close() }}
        </div>
    </div>
@stop