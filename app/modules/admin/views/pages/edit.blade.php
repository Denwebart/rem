@extends('admin::layouts.admin')

<?php
$title = 'Редактирование страницы';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <h1>
            <i class="fa fa-edit "></i>
            {{ $title }}
            <small>редактирование данных страницы</small>
        </h1>
        <ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active"><a href="{{ URL::route('admin.pages.index') }}">Страницы</a></li>
            <li>{{ Str::limit($page->getTitle(), 60, '...') }}</li>
        </ol>
    </div>

    <div class="content label-normal">
        <div class="row">
            <div class="col-md-8">
                <h4 class="no-margin-top">{{ $page->getTitle() }}</h4>
            </div>
            <div class="col-md-4">
                <div class="buttons margin-bottom-10 margin-top-10 display-inline-block pull-right">
                    <a href="javascript:void(0)" class="btn btn-success save-button">Сохранить</a>
                    <a href="{{ $backUrl }}" class="btn btn-primary">Отмена</a>
                </div>
            </div>
            {{ Form::model($page, ['method' => 'PUT', 'route' => ['admin.pages.update', $page->id], 'id' => 'pagesForm', 'files' => true]) }}
                @include('admin::pages._form')
                {{ Form::hidden('_token', csrf_token()) }}
            {{ Form::close() }}
        </div>
    </div>
@stop