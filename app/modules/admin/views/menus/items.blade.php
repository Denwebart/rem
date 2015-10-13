@extends('admin::layouts.admin')

<?php
$title = 'Меню сайта';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <div class="row">
            <div class="col-md-10 col-sm-9 col-xs-12">
                <h1>
                    <i class="fa fa-exclamation-triangle"></i>
                    {{ $title }}
                    <small></small>
                </h1>
            </div>
        </div>

        {{--<ol class="breadcrumb">--}}
            {{--<li><a href="{{ URL::to('admin') }}">Главная</a></li>--}}
            {{--<li><a href="{{ URL::route('admin.settings.index') }}">Настройки</a></li>--}}
            {{--<li class="active">{{ $title }}</li>--}}
        {{--</ol>--}}
    </div>
    <div class="content">
        <!-- Main row -->
        <div class="row">

            <div class="col-md-10 col-sm-9 col-xs-12 margin-bottom-15">
                <a href="{{ URL::route('admin.settings.index') }}" class="btn btn-dashed">
                    <span>Все настройки</span>
                </a>
                <a href="{{ URL::route('admin.rules.index') }}" class="btn btn-dashed">
                    <span>Правила сайта</span>
                </a>
                <a href="{{ URL::route('admin.notificationsMessages.index') }}" class="btn btn-dashed">
                    <span>Шаблоны уведомлений</span>
                </a>
                <a href="{{ URL::route('admin.menus.index') }}" class="btn btn-primary">
                    <span>Меню сайта</span>
                </a>
            </div>
            <div class="col-md-2 col-sm-3 col-xs-12 margin-bottom-15">
            </div>

            <div class="col-xs-6">
                <div class="box">
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        Заголовок пункта меню
                                    </th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="menus-list" class="sortable">
                                @foreach($menuItems as $item)
                                    <tr id="{{ $item->id }}">
                                        <td>
                                            {{ $item->menu_title }}
                                        </td>
                                        <td>Кнопки</td>
                                    </tr>
                                    @if(count($item->children))
                                        @foreach($item->children as $itemChild)
                                            <tr id="{{ $itemChild->id }}">
                                                <td>
                                                    --- {{ $itemChild->menu_title }}
                                                </td>
                                                <td>Кнопки</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>
    </div>
@stop

@section('script')
    @parent

    <script type="text/javascript">
        $(function() {
            $(".sortable").sortable({
                cursor: 'move',
                axis: 'y',
                update: function (event, ui) {
                    var positions = $(this).sortable('toArray');

                    $.ajax({
                        data: {positions: positions},
                        type: 'POST',
                        url: '{{ URL::route('admin.menus.changePosition', ['type' => $type]) }}',
                        beforeSend: function(request) {
                            return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                        }
                    })
                }
            });
        });
    </script>
@stop