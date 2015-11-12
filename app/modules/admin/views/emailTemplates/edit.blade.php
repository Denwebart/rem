@extends('admin::layouts.admin')

<?php
$title = 'Редактирование шаблона email письма (' . $emailTemplate->key . ')';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <h1><i class="fa fa-edit "></i>
            {{ $title }}
            <small></small></h1>
        <ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li><a href="{{ URL::route('admin.settings.index') }}">Настройки</a></li>
            <li class="active"><a href="{{ URL::route('admin.emailTemplates.index') }}">Шаблоны email писем</a></li>
            <li>{{ $title }}</li>
        </ol>
    </div>

    <div class="content label-normal">
        <div class="row">
            <div class="col-md-12">
                <h4 class="no-margin-top">{{ $emailTemplate->description }}</h4>
            </div>
            {{ Form::model($emailTemplate, ['method' => 'PUT', 'route' => ['admin.emailTemplates.update', $emailTemplate->id], 'id' => 'emailTemplateForm']) }}
                @include('admin::emailTemplates._form')
                {{ Form::hidden('_token', csrf_token()) }}
            {{ Form::close() }}
        </div>
    </div>
@stop