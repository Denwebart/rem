@extends('admin::layouts.admin')

<?php $params = isset($parentPage) ? ['id' => $parentPage->id] : []; ?>

@section('content')
<div class="page-head">
    <h1>Страницы
        <small>@if(isset($parentPage)) подпункты страницы "{{ $parentPage->getTitle() }}" @else все страницы сайта @endif</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('admin') }}">Главная</a></li>
        @if(isset($parentPage))
            <li class="active">
                <a href="{{ URL::route('admin.pages.index') }}">
                    Страницы
                </a>
            </li>
            <li>
                Подпункты страницы "{{ $parentPage->getTitle() }}"
            </li>
        @else
            <li class="active">Страницы</li>
        @endif
    </ol>
</div>

<div class="content">
    <!-- Main row -->
    <div class="row">
        <div class="col-xs-3">
            <div id="pages-tree">
                <ul class="nav nav-pages-tree nav-stacked">
                    <li class="active">
                        <a href="{{ URL::route('admin.pages.index') }}">
                            <i class="fa fa-clipboard"></i>
                            Страницы сайта
                            <span class="label pull-right">
                                {{ count(Page::all()) }}
                            </span>
                        </a>
                    </li>
                    @foreach(Page::whereParentId(0)->get() as $page)
                        <li{{ !$page->is_published ? ' class="not-published"' : ''}}>
                            @if($page->is_container && count($page->children))
                                <a href="javascript:void(0)" class="open" data-page-id="{{ $page->id }}">
                                    <i class="fa fa-folder" style="color: #F0AD4E; font-size: 18px"></i>
                                </a>
                                <a href="{{ URL::route('admin.pages.children', ['id' => $page->id]) }}" class="title">
                                    {{ $page->getTitle() }}
                                </a>
                            @else
                                <i class="fa fa-file-text-o" style="color: #293C4E"></i>
                                <span class="title">
                                    {{ $page->getTitle() }}
                                </span>
                            @endif
                            <a href="{{ URL::route('admin.pages.edit', ['id' => $page->id]) }}" class="label pull-right">
                                <i class="fa fa-edit"></i>
                            </a>

                        </li>
                    @endforeach

                    @section('script')
                        @parent

                        <script type="text/javascript">

                            // Открытие дерева
                            $("#pages-tree").on('click', '.open', function(e){
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
                                        success: function(response) {
                                            if(response.success) {
                                                link.parent().append(response.children);
                                                link.parent().find('.children').slideDown();
                                                link.find('i').removeClass('fa-folder').addClass('fa-folder-open');
                                            }
                                        }
                                    });
                                }
                            });


                        </script>

                    @endsection
                </ul>
            </div>
        </div>
        <div class="col-xs-9">
            <div class="box">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'ID', 'id', $params) }}
                                </th>
                                <th></th>
                                <th width="30%">
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Заголовок', 'title', $params) }}
                                </th>
                                <th>Родитель</th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Статус публикации', 'is_published', $params) }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Дата создания', 'created_at', $params) }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Дата обновления', 'updated_at', $params) }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink(Route::currentRouteName(), 'Дата публикации', 'published_at', $params) }}
                                </th>
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
                                <td>
                                    @if($page->is_container && count($page->children))
                                        <i class="fa fa-folder" style="color: #F0AD4E; font-size: 18px"></i>
                                    @else
                                        <i class="fa fa-file-text-o" style="color: #293C4E"></i>
                                    @endif
                                </td>
                                <td>{{ $page->getTitle() }}</td>
                                <td>{{ ($page->parent) ? $page->parent->getTitle() : 'Нет'}}</td>
                                <td>
                                    @if($page->is_published)
                                        <span class="label label-success">Опубликован</span>
                                    @else
                                        <span class="label label-warning">Не опубликован</span>
                                    @endif
                                </td>
                                <td>{{ DateHelper::dateFormat($page->created_at) }}</td>
                                <td>{{ DateHelper::dateFormat($page->updated_at) }}</td>
                                <td>{{ !is_null($page->published_at) ? DateHelper::dateFormat($page->published_at) : '-'}}</td>
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