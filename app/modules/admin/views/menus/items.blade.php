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

        {{--<ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">--}}
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

            <div class="col-md-6 col-sm-12 col-xs-12">
                <h4>Пункты меню</h4>
                <ul id="menus-list" class="sortable todo">
                    @foreach($menuItems as $item)
                        <li id="{{ $item->id }}">
                            <span class="title pull-left">
                                {{ $item->menu_title }}
                            </span>
                            <a href="{{ URL::route('admin.pages.edit', ['id' => $item->page->id]) }}" class="pull-right margin-right-5" title="Редактировать страницу" data-toggle="tooltip">
                                <i class="fa fa-edit"></i>
                            </a>
                            <div class="clearfix"></div>
                            @if(count($item->children))
                                <ul class="sortable-sublist sublist margin-top-10">
                                    @foreach($item->children as $itemChild)
                                        <li id="{{ $itemChild->id }}">
                                            <span class="title pull-left">
                                                {{ $itemChild->menu_title }}
                                            </span>
                                            <a href="{{ URL::route('admin.pages.edit', ['id' => $itemChild->page->id]) }}" class="pull-right" title="Редактировать страницу" data-toggle="tooltip">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="col-xs-6">
            </div>
        </div>
    </div>
@stop

@section('script')
    @parent

    <script type="text/javascript">
        $(function() {
            $(".sortable, .sortable-sublist").sortable({
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
                        },
                        success: function(response) {
                            if(response.success) {
                                $('#site-messages').prepend(response.message);
                                setTimeout(function() {
                                    hideSiteMessage($('.site-message'));
                                }, 2000);
                            }
                        },
                    })
                }
            });
        });
    </script>
@stop