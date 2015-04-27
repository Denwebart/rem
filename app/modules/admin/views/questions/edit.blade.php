@extends('admin::layouts.admin')

@section('content')
    <div class="page-head">
        <h1>Редактирование вопроса <small>редактирование вопроса пользователя</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active"><a href="{{ URL::route('admin.questions.index') }}">Вопросы</a></li>
            <li>{{ Str::limit($page->getTitle(), 60, '...') }}</li>
        </ol>
    </div>

    <div class="content label-normal">
        <div class="row">
            <div class="col-md-12">
                <h4 class="no-margin-top">{{ $page->getTitle() }}</h4>
            </div>
            {{ Form::model($page, ['method' => 'PUT', 'route' => ['admin.questions.update', $page->id], 'id' => 'registerForm']) }}
                @include('admin::questions._form')
            {{ Form::close() }}
        </div>
    </div>
@stop