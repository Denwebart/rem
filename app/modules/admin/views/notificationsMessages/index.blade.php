@extends('admin::layouts.admin')

<?php
$title = 'Шаблоны уведомлений';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <h1><i class="fa fa-exclamation-triangle"></i> {{ $title }} <small></small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li><a href="{{ URL::route('admin.settings.index') }}">Настройки</a></li>
            <li class="active">{{ $title }}</li>
        </ol>
    </div>

    <div class="content">
        <!-- Main row -->
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'ID', 'id') }}</th>
                                <th max-width="20%">{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Заголовок', 'description') }}</th>
                                {{--<th>Переменные</th>--}}
                                <th>Текст уведомления</th>
                                <th class="button-column"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($notificationsMessages as $notificationMessage)
                                <tr>
                                    <td>{{ $notificationMessage->id }}</td>
                                    <td>{{ $notificationMessage->description }}</td>
{{--                                    <td>{{ $notificationMessage->variables }}</td>--}}
                                    <td>{{ $notificationMessage->message }}</td>
                                    <td>
                                        <a class="btn btn-info btn-sm" href="{{ URL::route('admin.notificationsMessages.edit', $notificationMessage->id) }}">
                                            <i class="fa fa-edit "></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="pull-left">
                            {{ $notificationsMessages->links() }}
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>
    </div>
@stop