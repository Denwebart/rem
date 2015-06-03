@extends('admin::layouts.admin')

@section('content')
<div class="page-head">
    <h1>Теги
        <small>теги к статьям пользователей</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('admin') }}">Главная</a></li>
        <li class="active">Теги</li>
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
                                <th class="button-column">
                                    <a class="btn btn-success btn-sm" href="{{ URL::route('admin.tags.create') }}">
                                        <i class="fa fa-plus "></i> Создать
                                    </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($tags as $tag)
                            <tr>
                                <td>{{ $tag->id }}</td>
                                <td> </td>
                                <td>
                                    <a href="{{ URL::route('search', ['tag' => $tag->title]) }}" target="_blank">
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