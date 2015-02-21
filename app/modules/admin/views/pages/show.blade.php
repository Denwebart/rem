@extends('admin::layouts.admin')

@section('content')
    <div class="page-head">
        <h1>Просмотр страницы <small>информация о странице</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active"><a href="{{ URL::route('admin.pages.index') }}">Страницы</a></li>
            <li>{{ Str::limit($page->getTitle(), 60, '...') }}</li>
        </ol>
    </div>

    <div class="content">
        <!-- Main row -->
        <div class="row">
            <div class="col-md-12">
                <h4 class="no-margin-top">{{ $page->getTitle() }}</h4>
            </div>
            <div class="col-xs-12">

            </div>
        </div>
    </div>
@stop
