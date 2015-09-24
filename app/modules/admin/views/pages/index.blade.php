@extends('admin::layouts.admin')

<?php
$title = isset($parentPage)
    ? 'Подпункты страницы "' . $parentPage->getTitle() . '"'
    : 'Страницы';
View::share('title', $title);

$params = isset($parentPage) ? ['id' => $parentPage->id] : [];
?>

@section('content')
<div class="page-head">
    <div class="row">
        <div class="col-md-10 col-sm-9 col-xs-12">
            <h1>
                <i class="fa fa-file"></i>
                {{ $title }}
                @if(!isset($parentPage))
                    <small>все страницы сайта</small>
                @endif
            </h1>
        </div>
        <div class="col-md-2 col-sm-3 col-xs-12">
            <div class="buttons">
                <a class="btn btn-success btn-sm btn-full" href="{{ URL::route('admin.pages.create') }}">
                    <i class="fa fa-plus "></i> Создать
                </a>
            </div>
        </div>
    </div>
    {{--<ol class="breadcrumb">--}}
        {{--<li><a href="{{ URL::to('admin') }}">Главная</a></li>--}}
        {{--@if(isset($parentPage))--}}
            {{--<li class="active">--}}
                {{--<a href="{{ URL::route('admin.pages.index') }}">--}}
                    {{--Страницы--}}
                {{--</a>--}}
            {{--</li>--}}
            {{--<li>--}}
                {{--Подпункты страницы "{{ $parentPage->getTitle() }}"--}}
            {{--</li>--}}
        {{--@else--}}
            {{--<li class="active">Страницы</li>--}}
        {{--@endif--}}
    {{--</ol>--}}
</div>

<div class="content">
    <!-- Main row -->
    <div class="row">
        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
            <div id="pages-tree">
                <ul class="nav nav-pages-tree nav-stacked">
                    <li class="active">
                        <a href="{{ URL::route('admin.pages.index') }}">
                            <i class="fa fa-clipboard"></i>
                            <span>Все страницы</span>
                        </a>
                        <span class="pull-right">
                            {{ Page::all()->count() }}
                        </span>
                    </li>
                    @foreach(Page::whereParentId(0)->with('children')->get() as $page)
                        <li class="{{ ($page->is_container) ? 'category' : 'page' }}{{ !$page->is_published ? ' not-published' : '' }}{{ isset($parentPage) ? (($parentPage->id == $page->id) ? ' curent' : '') : '' }}">
                            @if($page->is_container)
                                <a href="{{ URL::route('admin.pages.children', ['id' => $page->id]) }}" class="open" data-page-id="{{ $page->id }}">
                                    <i class="fa fa-folder"></i>
                                </a>
                                <a href="{{ URL::route('admin.pages.children', ['id' => $page->id]) }}" class="title">
                                    {{ $page->getTitle() }}
                                    <span class="count">
                                        ({{ count($page->children) }})
                                    </span>
                                </a>
                            @else
                                <i class="fa fa-file-text-o"></i>
                                <span class="title">
                                    {{ $page->getTitle() }}
                                </span>
                            @endif
                            <a href="{{ URL::route('admin.pages.edit', ['id' => $page->id]) }}" class="edit pull-right">
                                <i class="fa fa-edit"></i>
                            </a>
                        </li>
                    @endforeach

                    @section('script')
                        @parent

                        <script type="text/javascript">
                            // Открытие дерева
                            $("#pages-tree").on('click', '.open', function(e){
                                var evt = e ? e : window.event;
                                (evt.preventDefault) ? evt.preventDefault() : evt.returnValue = false;

                                var link = $(this);
                                if (link.parent().find('.children').length) {
                                    var children = link.parent().find('.children');
                                    if (children.is(':visible')) {
                                        children.slideUp();
                                        link.find('i').removeClass('fa-folder-open').addClass('fa-folder');
                                    } else {
                                        children.slideDown();
                                        link.find('i').removeClass('fa-folder').addClass('fa-folder-open');
                                    }
                                } else {
                                    $.ajax({
                                        url: '<?php echo URL::route('admin.pages.openTree') ?>',
                                        dataType: "text json",
                                        type: "POST",
                                        data: {pageId: link.data('pageId')},
                                        beforeSend: function(request) {
                                            return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                                        },
                                        success: function(response) {
                                            if(response.success) {
                                                if(response.childrenCount) {
                                                    link.parent().append(response.children);
                                                    link.parent().find('.children').slideDown();
                                                    link.find('i').removeClass('fa-folder').addClass('fa-folder-open');
                                                } else {
                                                    window.location.href = link.attr('href');
                                                }
                                            }
                                        }
                                    });
                                }
                            });
                        </script>
                    @stop
                </ul>
            </div>
        </div>
        <div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
            <div class="count">
                Показано: <span>{{ $pages->count() }}</span>.
                Всего: <span>{{ $pages->getTotal() }}</span>.
            </div>

            {{ Form::open(['method' => 'GET', 'route' => ['admin.pages.autocomplete'], 'id' => 'search-pages-form']) }}
            <div class="row">
                <div class="col-md-8 col-sm-8 col-xs-10">
                    <div class="form-group">
                        {{ Form::text('query', null, [
                            'class' => 'form-control floating-label',
                            'id' => 'query',
                            'placeholder' => 'Введите заголовок статьи'
                        ]) }}
                    </div>
                </div>
                {{--<div class="col-md-2 col-sm-2 col-xs-2">--}}
                    {{--<div class="checkbox is-online">--}}
                        {{--<label>--}}
                            {{--{{ Form::checkbox('is_online', 1, $is_online, ['id' => 'is-online']); }}--}}
                            {{--<span class="text">Онлайн</span>--}}
                        {{--</label>--}}
                    {{--</div>--}}
                {{--</div>--}}
                <div class="col-md-2 col-sm-2 col-xs-12">
                    <button type="submit" class="btn btn-success btn-sm btn-full">
                        <i class="material-icons">search</i>
                        <span class="hidden-md hidden-sm">Найти</span>
                        <div class="ripple-wrapper"></div>
                    </button>
                </div>
            </div>
            {{ Form::close() }}

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
                                <th></th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Изображение', 'image', $params) }}
                                </th>
                                <th width="30%">
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Заголовок', 'title', $params) }}
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
                                    @if($page->is_container && count($page->children))
                                        <i class="fa fa-folder" style="color: #F0AD4E; font-size: 18px"></i>
                                    @else
                                        <i class="fa fa-file-text-o" style="color: #293C4E"></i>
                                    @endif
                                </td>
                                <td>
                                    {{ $page->getImage('mini', ['width' => '50px']) }}
                                </td>
                                <td>
                                    <a href="{{ URL::to($page->getUrl()) }}" target="_blank">
                                        {{ $page->getTitle() }}
                                    </a>
                                </td>
                                <td>
                                    @if($page->parent)
                                        <a href="{{ URL::to($page->parent->getUrl()) }}" target="_blank">
                                            {{ $page->parent->getTitle() }}
                                        </a>
                                    @else
                                        Нет
                                    @endif
                                </td>
                                <td>
                                    @if($page->is_published)
                                        <span class="label label-success">Опубликован</span>
                                    @else
                                        <span class="label label-warning">Не опубликован</span>
                                    @endif
                                </td>
                                <td>{{ DateHelper::dateFormat($page->created_at) }}</td>
                                <td>{{ !is_null($page->published_at) ? DateHelper::dateFormat($page->published_at) : '-'}}</td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="{{ URL::route('admin.pages.edit', $page->id) }}">
                                        <i class="fa fa-edit "></i>
                                    </a>

                                    @if(Auth::user()->isAdmin())
                                        {{ Form::open(array('method' => 'DELETE', 'route' => array('admin.pages.destroy', $page->id), 'class' => 'as-button')) }}
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
                                    @endif

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