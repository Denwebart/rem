@extends('admin::layouts.admin')

<?php
$title = 'Комментарии';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <h1>
            <i class="fa fa-comment"></i>
            {{ $title }}
            <small>комментарии к статьям</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
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
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Автор', 'user_id') }}</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'IP', 'ip_id') }}</th>
                                <th max-width="20%">{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Страница', 'page_id') }}</th>
                                <th max-width="30%">Комментарий</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Статус публикации', 'is_published') }}</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Дата создания', 'created_at') }}</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Дата публикации', 'published_at') }}</th>
                                <th class="button-column"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($comments as $comment)
                                <tr @if($comment->is_deleted) class="danger" @elseif($comment->created_at > $headerWidget->getLastActivity()) class="info" @endif>
                                    <td>{{ $comment->id }}</td>
                                    <td>
                                        @if($comment->user)
                                            <a href="{{ URL::route('user.profile', ['login' => $comment->user->getLoginForUrl()]) }}">
                                                {{ $comment->user->getAvatar('mini', ['width' => '25px']) }}
                                                {{ $comment->user->login }}
                                            </a>
                                        @else
                                            {{{ $comment->user_name }}}
                                            <br/>
                                            ({{{ $comment->user_email }}})
                                        @endif
                                    </td>
                                    <td>
                                        @if($comment->ip)
                                            {{ $comment->ip->ip }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($comment->page)
                                            <a href="{{ URL::to($comment->page->getUrl()) }}">
                                                {{ $comment->page->getTitle() }}
                                            </a>
                                        @else
                                            <i>страница удалена</i>
                                        @endif
                                    </td>
                                    <td>{{ $comment->comment }}</td>
                                    <td>
                                        @if(!$comment->is_deleted)
                                            @if($comment->is_published)
                                                <span class="label label-success">Опубликован</span>
                                            @else
                                                <span class="label label-warning">Ожидает модерации</span>
                                            @endif
                                        @else
                                            <span class="label label-danger">Удален</span>
                                        @endif
                                    </td>
                                    <td>{{ DateHelper::dateFormat($comment->created_at) }}</td>
                                    <td>{{ (('0000-00-00 00:00:00' != $comment->published_at)) ? DateHelper::dateFormat($comment->published_at) : '-'}}</td>
                                    <td>
                                        <a class="btn btn-info btn-sm" href="{{ URL::route('admin.comments.edit', $comment->id) }}">
                                            <i class="fa fa-edit "></i>
                                        </a>

                                        @if(Auth::user()->isAdmin())
                                            {{ Form::open(array('method' => 'DELETE', 'route' => array('admin.comments.destroy', $comment->id), 'class' => 'as-button')) }}
                                                <button type="submit" class="btn btn-danger btn-sm" name="destroy">
                                                    <i class='fa fa-trash-o'></i>
                                                </button>
                                                {{ Form::hidden('_token', csrf_token()) }}
                                            {{ Form::close() }}
                                        @endif

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