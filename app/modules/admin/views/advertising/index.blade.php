@extends('admin::layouts.admin')

<?php
$title = 'Реклама и виджеты';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <h1><i class="fa fa-usd"></i> {{ $title }} <small>рекламные блоки и виджеты на сайте</small></h1>
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
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Тип', 'type') }}</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Область', 'area') }}</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Позиция', 'position') }}</th>
                                <th max-width="20%">{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Заголовок', 'title') }}</th>
                                <th max-width="20%">{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Описание', 'description') }}</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Доступ', 'access') }}</th>
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
                                <tr class="advertising">
                                    <td>{{ $item->id }}</td>
                                    <td>{{ Advertising::$types[$item->type] }}</td>
                                    <td>{{ Advertising::$areas[$item->area] }}</td>
                                    <td>{{ $item->position }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>
                                        @if(Advertising::TYPE_ADVERTISING == $item->type)
                                            {{ $item->description }}
                                        @else
                                            {{ Advertising::$widgets[$item->code] }} (кол-во: {{ $item->limit }})
                                            @if($item->description)
                                                <hr style="margin: 0">
                                                {{ $item->description }}
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{ Advertising::$access[$item->access] }}</td>
                                    <td>
                                        <!-- Отключить/выключить рекламный блок -->
                                        <a href="javascript:void(0)" class="change-active-status" data-id="{{ $item->id }}" data-is-active="{{ $item->is_active }}" title="{{ $item->is_active ? 'Выключить этот рекламный блок.' : 'Включить этот рекламный блок.' }}">
                                            @if($item->is_active)
                                                <span class="label label-success">Включен</span>
                                            @else
                                                <span class="label label-warning">Выключен</span>
                                            @endif
                                        </a>
                                    </td>
                                    <td>
                                        <a class="btn btn-info btn-sm" href="{{ URL::route('admin.advertising.edit', $item->id) }}">
                                            <i class="fa fa-edit "></i>
                                        </a>

                                        {{ Form::open(array('method' => 'DELETE', 'route' => array('admin.advertising.destroy', $item->id), 'class' => 'as-button')) }}
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

        $('.advertising').on('click', '.change-active-status', function(){
            var $button = $(this),
                    isActive = $button.attr('data-is-active'),
                    advertisingId = $button.data('id');
            $.ajax({
                url: '/admin/advertising/changeActiveStatus/' + advertisingId,
                dataType: "text json",
                type: "POST",
                data: {is_active: isActive},
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function(response) {
                    if(response.success){
                        console.log($('[data-advertising-id='+ advertisingId +']'));
                        if(response.isActive) {
                            $button.attr('title', 'Выключить этот рекламный блок.').html('<span class="label label-success">Включен</span>');
                        } else {
                            $button.attr('title', 'Включить этот рекламный блок.').html('<span class="label label-warning">Выключен</span>');
                        }
                        $button.attr('data-is-active', response.isActive);
                    } else {
                        alert(response.message)
                    }
                }
            });
        });
    </script>
@stop