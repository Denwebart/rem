@extends('admin::layouts.admin')

@section('content')
    <div class="page-head">
        <h1>Реклама  <small>рекламные блоки на сайте</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active">Реклама</li>
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
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Позиция', 'position') }}</th>
                                <th max-width="20%">{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Заголовок', 'title') }}</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Текст', 'text') }}</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Статус', 'is_active') }}</th>
                                <th class="button-column">
                                    <a class="btn btn-success btn-sm" href="{{ URL::route('admin.advertising.create') }}">
                                        <i class="fa fa-plus "></i> Создать
                                    </a>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($advertising as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->position }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->text }}</td>
                                    <td>
                                        @if($item->is_active)
                                            <span class="label label-success">Активно</span>
                                        @else
                                            <span class="label label-warning">Неактивно</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-info btn-sm" href="{{ URL::route('admin.advertising.edit', $item->id) }}">
                                            <i class="fa fa-edit "></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="pull-left">
                            {{ $advertising->links() }}
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