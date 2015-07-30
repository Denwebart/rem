@extends('admin::layouts.admin')

<?php
$title = 'Теги';
View::share('title', $title);
?>

@section('content')
<div class="page-head">
    <h1><i class="fa fa-tags"></i> {{ $title }}
        <small>теги к статьям пользователей</small>
    </h1>

    <ol class="breadcrumb">
        <li><a href="{{ URL::to('admin') }}">Главная</a></li>
        <li class="active">{{ $title }}</li>
    </ol>
</div>

<div class="content">
    <!-- Main row -->
    <div class="row">
        <div class="col-md-12">
            <a href="{{ URL::route('admin.tags.merge') }}" class="btn btn-primary">Объединение тегов</a>
        </div>

        <div class="col-md-12">
            <div class="box">
                <div class="box-title">
                    <h3>Добавить тег</h3>
                </div>
                <div class="box-body row">
                    {{ Form::model($tag, ['method' => 'POST', 'route' => ['admin.tags.store'], 'id' => 'tagsForm', 'files' => true]) }}
                        <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('image', 'Изображение') }}<br/>
                                {{ Form::file('image', ['title' => 'Загрузить изображение', 'class' => 'btn btn-primary file-inputs']) }}
                                {{ $errors->first('image') }}
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group">
                                {{ Form::label('title', 'Тег') }}
                                {{ Form::text('title', $tag->title, ['class' => 'form-control', 'placeholder' => 'Новый тег']) }}
                                {{ $errors->first('title') }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            {{ Form::submit('Сохранить', ['class' => 'btn btn-success margin-top-25']) }}
                        </div>
                        {{ Form::hidden('_token', csrf_token()) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div><!-- ./col -->

        <div class="col-xs-12">
            <div class="box">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'ID', 'id') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Изображение', 'image') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Заголовок', 'title') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Статьи по тегу', 'pages') }}
                                </th>
                                <th class="button-column"></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($tags as $tag)
                            <tr>
                                <td>{{ $tag->id }}</td>
                                <td>{{ $tag->getImage(null, ['width' => '50px']) }}</td>
                                <td>
                                    <a href="{{ URL::route('journal.tag', ['journalAlias' => Config::get('settings.journalAlias'), 'tag' => $tag->title]) }}" target="_blank">
                                        {{ $tag->title }}
                                    </a>
                                </td>
                                <td>
                                    {{ count($tag->pages) }}
                                </td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="{{ URL::route('admin.tags.edit', $tag->id) }}">
                                        <i class="fa fa-edit "></i>
                                    </a>

                                    {{ Form::open(array('method' => 'DELETE', 'route' => array('admin.tags.destroy', $tag->id), 'class' => 'as-button')) }}
                                        <button type="submit" class="btn btn-danger btn-sm" name="destroy">
                                            <i class='fa fa-trash-o'></i>
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

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="pull-left">
                        {{ SortingHelper::paginationLinks($tags) }}
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
</div>
@stop

@section('script')
    @parent

    <!-- File Input -->
    <script src="/backend/js/plugins/bootstrap-file-input/bootstrap-file-input.js" type="text/javascript"></script>
    <script type="text/javascript">
        $('.file-inputs').bootstrapFileInput();

        $(".file-inputs").on("change", function(){
            var file = this.files[0];
            if (file.size > 5242880) {
                $(this).parent().parent().append('Недопустимый размер файла.');
            }
        });
    </script>

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