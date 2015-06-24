@extends('admin::layouts.admin')

<?php
$title = 'Письма';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <h1>{{ $title }} <small>отправленные через контактную форму</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active">{{ $title }}</li>
        </ol>
    </div>

    <div class="content">
        <!-- Main row -->
        <div class="mailbox row">
            <div class="col-lg-12">
                <div class="box">
                    <div class="box-title">
                        <i class="fa fa-inbox"></i>
                        <h3>Почтовый ящик</h3>
                        <div class="pull-right box-toolbar">
                            <a href="#" class="btn btn-link btn-xs"><i class="fa fa-cog"></i></a>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-4">
                                <ul class="nav nav-pills nav-stacked">
                                    <li class="active">
                                        <a href="{{ URL::route('admin.letters.index') }}"><i class="fa fa-inbox"></i> Входящие письма
                                            @if($newLetters = count($headerWidget->newLetters()))
                                                <span class="label pull-right">
                                                    {{ $newLetters }}
                                                </span>
                                            @endif
                                        </a>
                                    </li>
                                    <li><a href="#"><i class="fa fa-envelope"></i> Отправленные письма</a></li>
                                    <li>
                                        <a href="{{ URL::route('admin.letters.trash') }}"><i class="fa fa-trash-o"></i> Удаленные письма
                                            @if($deletedLetters = count($headerWidget->deletedLetters()))
                                                <span class="label label-danger pull-right">
                                                    {{ $deletedLetters }}
                                                </span>
                                            @endif
                                        </a>
                                    </li>
                                    {{--<li><a href="#"><i class="fa fa-star"></i> Важные письма</a></li>--}}
                                </ul>

                                <div class="mailbox-buttons">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary no-radius dropdown-toggle" data-toggle="dropdown">Выбрать действие <i class="fa fa-paper-plane"></i></button>
                                        <ul class="dropdown-menu">
                                            <li><a href="#">Отметить как прочитанное</a></li>
                                            <li><a href="#">Отметить как непрочитанное</a></li>
                                            <li><a href="#">Удалить</a></li>
                                        </ul>
                                    </div>
                                    <button type="button" class="btn btn-success no-radius"><i class="fa fa-plus"></i></button>
                                    <button type="button" class="btn btn-danger no-radius"><i class="fa fa-trash-o"></i></button>
                                </div>

                                <div class="box-bordered clearfix">
                                    <input type="text" class="form-control" placeholder="Тема" />
                                    <input type="text" class="form-control" placeholder="Email" />
                                    <textarea class="form-control" placeholder="Сообщение" rows="8"></textarea>
                                    <button type="submit" class="btn btn-danger no-radius pull-left">Отмена</button>
                                    <button type="submit" class="btn btn-success no-radius pull-right">Отправить</button>
                                </div>
                            </div>
                            <div class="col-md-9 col-sm-8">

                                <div class="mailbox-tools clearfix">
                                    <div class="pull-left">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info no-radius dropdown-toggle" data-toggle="dropdown">Выбрать действие <i class="fa fa-paper-plane"></i></button>
                                            <ul class="dropdown-menu">
                                                <li><a href="#">Отметить как прочитанное</a></li>
                                                <li><a href="#">Отметить как непрочитанное</a></li>
                                                <li><a href="#">Удалить</a></li>
                                            </ul>
                                        </div>
                                        <button type="button" class="btn btn-success no-radius"><i class="fa fa-plus"></i></button>
                                        <button type="button" class="btn btn-danger no-radius"><i class="fa fa-trash-o"></i></button>
                                    </div>
                                </div>

                                <div class="table-responsive scroll">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>
                                                {{ SortingHelper::sortingLink('admin.letters.index', 'ID', 'id') }}
                                            </th>
                                            <th></th>
                                            <th></th>
                                            <th>
                                                {{ SortingHelper::sortingLink('admin.letters.index', 'Тема', 'subject') }}
                                            </th>
                                            <th>
                                                {{ SortingHelper::sortingLink('admin.letters.index', 'Имя', 'name') }}
                                            </th>
                                            <th>
                                                {{ SortingHelper::sortingLink('admin.letters.index', 'Email', 'email') }}
                                            </th>
                                            <th>
                                                {{ SortingHelper::sortingLink('admin.letters.index', 'Дата создания', 'created_at') }}
                                            </th>
                                            <th>
                                                {{ SortingHelper::sortingLink('admin.letters.index', 'Дата прочтения', 'read_at') }}
                                            </th>
                                            <th class="button-column"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($letters as $letter)
                                                <tr{{ ($letter->read_at) ? '' : ' class="unread"' }}>
                                                    <td class="small">{{ $letter->id }}</td>
                                                    <td class="small"><input type="checkbox" /></td>
                                                    <td class="small"><i class="fa fa-star"></i></td>
                                                    <td class="subject">{{ $letter->subject }}</td>
                                                    <td class="name">
                                                        @if($letter->user)
                                                            <a href="{{ URl::route('user.profile', ['login' => $letter->user->getLoginForUrl()]) }}" target="_blank">
                                                                {{ $letter->user->login }}
                                                            </a>
                                                        @else
                                                            {{ $letter->user_name }}
                                                        @endif
                                                    </td>
                                                    <td class="email">
                                                        @if($letter->user)
                                                            <a href="{{ URl::route('user.profile', ['login' => $letter->user->getLoginForUrl()]) }}" target="_blank">
                                                                {{ $letter->user->email }}
                                                            </a>
                                                        @else
                                                            {{ $letter->user_email }}
                                                        @endif
                                                    </td>
                                                    <td class="time">{{ DateHelper::dateFormat($letter->created_at) }}</td>
                                                    <td class="time">{{ ($letter->read_at) ? DateHelper::dateFormat($letter->read_at) : '-'}}</td>
                                                    <td>
                                                        <a class="btn btn-primary btn-sm" href="{{ URL::route('admin.letters.show', $letter->id) }}">
                                                            <i class="fa fa-search-plus"></i>
                                                        </a>
                                                        {{ Form::open(array('method' => 'DELETE', 'route' => array('admin.letters.markAsDeleted', $letter->id), 'class' => 'destroy as-button')) }}
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
                                                                        <p>Вы уверены, что хотите переместить письмо в корзину?</p>
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
                                        {{ SortingHelper::paginationLinks($letters) }}
                                    </div>
                                </div><!-- /.table-responsive -->
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div>
                    <div class="box-footer">
                        <div class="input-group">
                            <input class="form-control" placeholder="Поиск письма..."/>
                            <div class="input-group-btn">
                                <button class="btn btn-success"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--<div class="content">--}}
        {{--<!-- Main row -->--}}
        {{--<div class="row">--}}
            {{--<div class="col-xs-12">--}}
                {{--<div class="box">--}}
                    {{--<div class="box-body table-responsive no-padding">--}}
                        {{--<table class="table table-hover table-striped">--}}
                            {{--<thead>--}}
                            {{--<tr>--}}
                                {{--<th>ID</th>--}}
                                {{--<th>Тема</th>--}}
                                {{--<th>Имя</th>--}}
                                {{--<th>Email</th>--}}
                                {{--<th>Дата создания</th>--}}
                                {{--<th>Дата Прочтения</th>--}}
                                {{--<th class="button-column">--}}
                                    {{--<a class="btn btn-success btn-sm" href="{{ URL::route('admin.pages.create') }}">--}}
                                        {{--<i class="fa fa-plus "></i> Создать--}}
                                    {{--</a>--}}
                                {{--</th>--}}
                            {{--</tr>--}}
                            {{--</thead>--}}
                            {{--<tbody>--}}
                            {{--@foreach($letters as $letter)--}}
                                {{--<tr>--}}
                                    {{--<td>{{ $letter->id }}</td>--}}
                                    {{--<td>{{ $letter->subject }}</td>--}}
                                    {{--<td>{{ $letter->name }}</td>--}}
                                    {{--<td>{{ $letter->email }}</td>--}}
                                    {{--<td>{{ DateHelper::dateFormat($letter->created_at) }}</td>--}}
                                    {{--<td>{{ (('0000-00-00 00:00:00' != $letter->read_at)) ? DateHelper::dateFormat($letter->read_at) : '-'}}</td>--}}
                                    {{--<td>--}}
                                        {{--<a class="btn btn-primary btn-sm" href="{{ URL::route('admin.letters.show', $letter->id) }}">--}}
                                            {{--<i class="fa fa-search-plus "></i>--}}
                                        {{--</a>--}}
                                        {{--{{ Form::open(array('method' => 'DELETE', 'route' => array('admin.letters.destroy', $letter->id), 'class' => 'destroy')) }}--}}
                                        {{--<button type="submit" class="btn btn-danger btn-sm" name="destroy">--}}
                                            {{--<i class='fa fa-trash-o'></i>--}}
                                        {{--</button>--}}
                                        {{--{{ Form::close() }}--}}

                                        {{--<div id="confirm" class="modal fade">--}}
                                            {{--<div class="modal-dialog">--}}
                                                {{--<div class="modal-content">--}}
                                                    {{--<div class="modal-header">--}}
                                                        {{--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>--}}
                                                        {{--<h4 class="modal-title">Удаление</h4>--}}
                                                    {{--</div>--}}
                                                    {{--<div class="modal-body">--}}
                                                        {{--<p>Вы уверены, что хотите удалить?</p>--}}
                                                    {{--</div>--}}
                                                    {{--<div class="modal-footer">--}}
                                                        {{--<button type="button" class="btn btn-success" data-dismiss="modal" id="delete">Да</button>--}}
                                                        {{--<button type="button" class="btn btn-primary" data-dismiss="modal">Нет</button>--}}
                                                    {{--</div>--}}
                                                {{--</div><!-- /.modal-content -->--}}
                                            {{--</div><!-- /.modal-dialog -->--}}
                                        {{--</div><!-- /.modal -->--}}

                                    {{--</td>--}}
                                {{--</tr>--}}
                            {{--@endforeach--}}
                            {{--</tbody>--}}
                        {{--</table>--}}
                        {{--<div class="pull-left">--}}
                            {{--{{ $letters->links() }}--}}
                        {{--</div>--}}
                    {{--</div><!-- /.box-body -->--}}
                {{--</div><!-- /.box -->--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
@stop

@section('script')
    @parent

    <!-- Confirm for delete -->
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

    <!-- Inbox -->
    <script type="text/javascript">
        $(function() {
            //iCheck
            $("input[type='checkbox'], input[type='radio']").iCheck({
                checkboxClass: 'icheckbox_minimal',
                radioClass: 'iradio_minimal'
            });

            // box scroll
            $('.scroll').slimScroll({
                height: '600px'
            });
        });
    </script>

@stop