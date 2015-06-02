@extends('admin::layouts.admin')

<?php $params = isset($parentPage) ? ['id' => $parentPage->id] : []; ?>

@section('content')
    <div class="page-head">
        <h1>Вопросы
            <small>вопросы пользователей</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active">Вопросы</li>
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
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'ID', 'id', $params) }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Автор', 'user_id', $params) }}
                                </th>
                                <th width="30%">
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Заголовок', 'title', $params) }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Ответы', 'publishedComments', $params) }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Категория', 'parent_id', $params) }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Статус публикации', 'is_published', $params) }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Дата создания', 'created_at', $params) }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Дата публикации', 'published_at', $params) }}
                                </th>
                                <th class="button-column">
                                    <a class="btn btn-success btn-sm" href="{{ URL::route('admin.questions.create') }}">
                                        <i class="fa fa-plus "></i> Создать
                                    </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($pages as $page)
                            <tr>
                                <td>{{ $page->id }}</td>
                                <td>
                                    <a href="{{ URL::route('user.profile', ['login' => $page->user->getLoginForUrl()]) }}">
                                        {{ $page->user->getAvatar('mini', ['width' => '25px']) }}
                                        {{ $page->user->login }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ URL::to($page->getUrl()) }}" target="_blank">
                                        {{ $page->getTitle() }}
                                    </a>
                                </td>
                                <td>
                                    @if(count($page->bestComments))
                                        <i class="glyphicon glyphicon-ok"></i>
                                    @endif
                                    <a href="{{ URL::to($page->getUrl()) }}" target="_blank">
                                        {{ count($page->publishedComments) }}
                                    </a>
                                </td>
                                <td>{{ ($page->parent) ? $page->parent->getTitle() : 'Нет'}}</td>
                                <td>
                                    @if($page->is_published)
                                        <span class="label label-success">Опубликован</span>
                                    @else
                                        <span class="label label-warning">Ожидает модерации</span>
                                    @endif
                                </td>
                                <td>{{ DateHelper::dateFormat($page->created_at) }}</td>
                                <td>{{ !is_null($page->published_at) ? DateHelper::dateFormat($page->published_at) : '-'}}</td>
                                <td>
                                    <a class="btn btn-success btn-sm" href="{{ URL::route('admin.questions.show', $page->id) }}">
                                        <i class="fa fa-search-plus "></i>
                                    </a>
                                    <a class="btn btn-info btn-sm" href="{{ URL::route('admin.questions.edit', $page->id) }}">
                                        <i class="fa fa-edit "></i>
                                    </a>

                                    @if(Auth::user()->isAdmin())
                                    {{ Form::open(array('method' => 'DELETE', 'route' => array('admin.questions.destroy', $page->id), 'class' => 'as-button')) }}
                                        <button type="submit" class="btn btn-danger btn-sm" name="destroy">
                                            <i class='fa fa-trash-o'></i>
                                        </button>
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
                        {{ SortingHelper::paginationLinks($pages) }}
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