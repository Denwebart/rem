@extends('admin::layouts.admin')

@section('content')
<div class="page-head">
    <h1>Страницы  <small>все страницы сайта</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('admin') }}">Главная</a></li>
        <li class="active">Страницы</li>
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
                                <th>ID</th>
                                <th>Родитель</th>
                                <th>Заголовок</th>
                                <th>Статус публикации</th>
                                <th>Дата создания</th>
                                <th>Дата обновления</th>
                                <th>Дата публикации</th>
                                <th class="button-column">
                                    <a class="btn btn-success btn-sm" href="{{ URL::route('admin.pages.create') }}">
                                        <i class="fa fa-plus "></i> Создать
                                    </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($pages as $page)
                            <tr>
                                <td>{{ $page->id }}</td>
                                <td>{{ ($page->parent) ? $page->parent->getTitle() : 'Нет'}}</td>
                                <td>{{ $page->getTitle() }}</td>
                                <td>
                                    @if($page->is_published)
                                        <span class="label label-success">Опубликован</span>
                                    @else
                                        <span class="label label-warning">Не опубликован</span>
                                    @endif
                                </td>
                                <td>{{ DateHelper::dateFormat($page->created_at) }}</td>
                                <td>{{ DateHelper::dateFormat($page->updated_at) }}</td>
                                <td>{{ (('0000-00-00 00:00:00' != $page->published_at)) ? DateHelper::dateFormat($page->published_at) : '-'}}</td>
                                <td>
                                    {{--<a class="btn btn-success btn-sm" href="{{ URL::route('admin.pages.show', $page->id) }}">--}}
                                        {{--<i class="fa fa-search-plus "></i>--}}
                                    {{--</a>--}}
                                    <a class="btn btn-info btn-sm" href="{{ URL::route('admin.pages.edit', $page->id) }}">
                                        <i class="fa fa-edit "></i>
                                    </a>
                                    {{ Form::open(array('method' => 'DELETE', 'route' => array('admin.pages.destroy', $page->id), 'class' => 'as-button')) }}
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
                        {{ $pages->links() }}
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
</div>
@stop

@section('script')
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