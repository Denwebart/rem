@extends('admin::layouts.admin')

@section('content')
    <div class="page-head">
        <h1>Комментарии  <small>комментарии к статьям</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active">Комментарии</li>
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
                                <th max-width="20%">Страница</th>
                                <th>Пользователь</th>
                                <th max-width="30%">Комментарий</th>
                                <th>Статус публикации</th>
                                <th>Дата создания</th>
                                <th>Дата публикации</th>
                                <th class="button-column">
                                    <a class="btn btn-success btn-sm" href="{{ URL::route('admin.comments.create') }}">
                                        <i class="fa fa-plus "></i> Создать
                                    </a>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($comments as $comment)
                                <tr>
                                    <td>{{ $comment->id }}</td>
                                    <td>{{ $comment->page->getTitle() }}</td>
                                    <td>{{ $comment->user->login }}</td>
                                    <td>{{ $comment->comment }}</td>
                                    <td>
                                        @if($comment->is_published)
                                            <span class="label label-success">Опубликован</span>
                                        @else
                                            <span class="label label-warning">Не опубликован</span>
                                        @endif
                                    </td>
                                    <td>{{ DateHelper::dateFormat($comment->created_at) }}</td>
                                    <td>{{ (('0000-00-00 00:00:00' != $comment->published_at)) ? DateHelper::dateFormat($comment->published_at) : '-'}}</td>
                                    <td>
                                        {{--<a class="btn btn-success btn-sm" href="{{ URL::route('admin.comments.show', $comment->id) }}">--}}
                                        {{--<i class="fa fa-search-plus "></i>--}}
                                        {{--</a>--}}
                                        <a class="btn btn-info btn-sm" href="{{ URL::route('admin.comments.edit', $comment->id) }}">
                                            <i class="fa fa-edit "></i>
                                        </a>
                                        {{ Form::open(array('method' => 'DELETE', 'route' => array('admin.comments.destroy', $comment->id), 'class' => 'as-button')) }}
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
                            {{ $comments->links() }}
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