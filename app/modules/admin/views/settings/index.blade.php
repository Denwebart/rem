@extends('admin::layouts.admin')

<?php
$title = 'Просмотр вопроса';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <h1>
            <i class="fa fa-cogs"></i>
            Настройки
            <small>настройки сайта</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active">Настройки</li>
        </ol>
    </div>
    <div class="content">
        <!-- Main row -->
        <div class="row">

            <div class="col-xs-12">
                <a href="{{ URL::route('admin.rules.index') }}" class="btn btn-primary">
                    Правила сайта
                </a>
                <a href="{{ URL::route('admin.notificationsMessages.index') }}" class="btn btn-primary">
                    Шаблоны уведомлений
                </a>
            </div>

            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'ID', 'id') }}</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Ключ', 'key') }}</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Категория', 'key') }}</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Тип', 'type') }}</th>
                                <th max-width="20%">{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Заголовок', 'title') }}</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Описание', 'description') }}</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Значение', 'value') }}</th>
                                <th>{{ SortingHelper::sortingLink(Route::currentRouteName(), 'Статус', 'is_published') }}</th>
                                <th class="button-column"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($settings as $setting)
                                <tr>
                                    <td>{{ $setting->id }}</td>
                                    <td>{{ $setting->key }}</td>
                                    <td>{{ $setting->category }}</td>
                                    <td>{{ Setting::$types[$setting->type] }}</td>
                                    <td>{{ $setting->title }}</td>
                                    <td>{{ $setting->description }}</td>
                                    <td>
                                        @if($setting->key != 'categoriesOnMainPage')
                                            {{ $setting->value }}
                                        @else
                                            <ul>
                                                @foreach(Page::whereIn('id', explode(',', $setting->value))->whereParentId(0)->get() as $item)
                                                    <li>
                                                        {{ $item->getTitle() }}
                                                        @foreach(Page::whereIn('id', explode(',', $setting->value))->where('parent_id', '!=', 0)->get() as $subitem)
                                                            <ul>
                                                                @if($item->id == $subitem->parent_id)
                                                                    <li>
                                                                        {{ $subitem->getTitle() }}
                                                                    </li>
                                                                @endif
                                                            </ul>
                                                        @endforeach
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                    <td>
                                        @if($setting->is_active)
                                            <span class="label label-success">Активна</span>
                                        @else
                                            <span class="label label-warning">Неактивна</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-info btn-sm" href="{{ URL::route('admin.settings.edit', $setting->id) }}">
                                            <i class="fa fa-edit "></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="pull-left">
                            {{ $settings->links() }}
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