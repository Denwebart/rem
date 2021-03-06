@extends('admin::layouts.admin')

<?php
$title = (Advertising::TYPE_ADVERTISING == $advertising->type)
        ? 'Редактирование рекламного блока'
        : 'Редактирование виджета';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <h1>
            <i class="fa fa-edit "></i>
            {{ $title }}
            <small>редактирование
                @if(Advertising::TYPE_ADVERTISING == $advertising->type)
                    рекламного блока
                @else
                    виджета
                @endif
            </small>
        </h1>
        <ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active"><a href="{{ URL::route('admin.advertising.index') }}">Реклама и виджеты</a></li>
            <li>Редактирование {{ $advertising->title }}</li>
        </ol>
    </div>

    <div class="content label-normal">
        <div class="row">
            <div class="col-md-8">
                <h4 class="no-margin-top">{{ $advertising->title }}</h4>
            </div>
            <div class="col-md-4">
                <div class="buttons margin-bottom-10 margin-top-10 display-inline-block pull-right">
                    <a href="javascript:void(0)" class="btn btn-success pull-left margin-right-5 save-button">Сохранить</a>
                    <a href="{{ $backUrl }}" class="btn btn-primary pull-left margin-right-10">Отмена</a>

                    {{ Form::open(array('method' => 'DELETE', 'route' => array('admin.advertising.destroy', $advertising->id, 'backUrl' => urlencode(URL::previous())), 'class' => 'as-button')) }}
                    <button type="submit" class="btn btn-danger pull-left" name="destroy">
                        <i class='fa fa-trash-o'></i>
                        Удалить
                    </button>
                    {{ Form::hidden('_token', csrf_token()) }}
                    {{ Form::close() }}

                    <div id="confirm" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h4 class="modal-title">Удаление</h4>
                                </div>
                                <div class="modal-body">
                                    <p>Вы уверены, что хотите удалить?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-success" data-dismiss="modal" id="delete">Да</button>
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Нет</button>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                </div>
            </div>
            {{ Form::model($advertising, ['method' => 'PUT', 'route' => ['admin.advertising.update', $advertising->id], 'id' => 'advertisingForm']) }}
                @include('admin::advertising._form')
                {{ Form::hidden('_token', csrf_token()) }}
            {{ Form::close() }}
        </div>
    </div>
@stop

@section('script')
    @parent

    <script type="text/javascript">
        $('button[name="destroy"]').on('click', function(e){
            var $form=$(this).closest('form');
            e.preventDefault();
            $('#confirm').modal({ backdrop: 'static', keyboard: false })
                    .one('click', '#delete', function() {
                        $form.trigger('submit'); // submit the form
                    });
        });
    </script>
@stop