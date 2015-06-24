@extends('admin::layouts.admin')

<?php
$title = 'Пользователи';
View::share('title', $title);
?>

@section('content')
    <div class="page-head">
        <h1>{{ $title }} <small>все пользователи сайта</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active">{{ $title }}</li>
        </ol>
    </div>

    <div class="content">
        <!-- Main row -->
        <div class="row">

            <div class="col-xs-12">
                <a href="{{ URL::route('admin.users.index') }}" class="btn btn-primary btn-outline">
                    Все пользователи
                </a>
                <a href="{{ URL::route('admin.users.bannedUsers') }}" class="btn btn-primary">
                    Забаненные пользователи
                </a>
                <a href="{{ URL::route('admin.users.bannedIps') }}" class="btn btn-primary">
                    Забаненные IP-адреса
                </a>
            </div>

            <div id="message"></div>

            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.index', 'ID', 'id') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.index', 'Фото', 'avatar') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.index', 'Роль', 'role') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.index', 'Логин', 'login') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.index', 'Имя', 'fullname') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.index', 'Email', 'email') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.index', 'Баллы', 'points') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.index', 'Статус', 'is_active') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.index', 'Дата регистрации', 'created_at') }}
                                </th>
                                <th>
                                    Награды
                                </th>
                                <th class="button-column">
                                    <a class="btn btn-success btn-sm" href="{{ URL::route('admin.users.create') }}">
                                        <i class="fa fa-plus "></i> Создать
                                    </a>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr data-user-id="{{ $user->id }}" @if($user->is_banned) class="danger" @endif>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        <a href="{{ URL::route('user.profile', ['login' => $user->login]) }}">
                                            {{ $user->getAvatar('mini') }}
                                        </a>
                                    </td>
                                    <td>
                                        @if($user->isAdmin() && 1 == $user->id)
                                            {{ User::$roles[$user->role] }}
                                        @else
                                            {{ Form::open([
                                                'action' => ['AdminUsersController@changeRole', $user->id],
                                                'id' => 'changeRole-form-' . $user->id,
                                            ]) }}

                                            {{ Form::select('role', ($user->hasRole()) ? User::$roles : [User::ROLE_NONE => 'Не назначена'] + User::$roles, $user->role, ['data-role' => $user->role]) }}

                                            <div class="buttons pull-right"></div>

                                            {{ Form::close() }}
                                        @endif
                                    </td>
                                    <td>{{ $user->login }}</td>
                                    <td>{{ $user->getFullName() }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->points }}</td>
                                    <td>
                                        @if($user->is_active)
                                            <span class="label label-success">Активный</span>
                                        @else
                                            <span class="label label-warning">Неактивный</span>
                                        @endif
                                    </td>
                                    <td>{{ DateHelper::dateFormat($user->created_at) }}</td>
                                    <td>
                                        @foreach($user->honors as $honor)
                                            <a href="{{ URL::route('admin.honors.show', ['id' => $honor->id]) }}">
                                                {{ $honor->getImage(null, ['width' => '25px']) }}
                                            </a>
                                        @endforeach
                                    </td>
                                    <td class="buttons">
                                        <a class="btn btn-info btn-sm" href="{{ URL::route('user.edit', ['login' => $user->getLoginForUrl()]) }}" title="Редактировать">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        {{ Form::open(array('method' => 'DELETE', 'route' => array('admin.users.destroy', $user->id), 'class' => 'as-button')) }}
                                        <button type="submit" class="btn btn-danger btn-sm" name="destroy" title="Удалить">
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

                                        <!-- Бан пользователя -->
                                        @if(!$user->isAdmin())
                                            @if(!$user->is_banned)
                                                <a class="btn btn-primary btn-sm banned-link ban" href="javascript:void(0)" title="Забанить" data-id="{{ $user->id }}">
                                                    <i class="fa fa-lock"></i>
                                                </a>
                                            @else
                                                <a class="btn btn-primary btn-sm banned-link unban" href="javascript:void(0)" title="Разбанить" data-id="{{ $user->id }}">
                                                    <i class="fa fa-unlock"></i>
                                                </a>
                                            @endif
                                        @endif

                                        <div class="modal fade unban-modal" data-unban-modal-id="{{ $user->id }}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                        <h4 class="modal-title">Снятие бана с пользователя</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Вы уверены, что хотите разбанить пользователя {{ $user->login }}?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-success unban-confirm" data-dismiss="modal" data-id="{{ $user->id }}">Разбанить</button>
                                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div><!-- /.modal -->

                                        <div class="modal fade ban-modal" data-ban-modal-id="{{ $user->id }}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                        <h4 class="modal-title">Бан пользователя {{ $user->login }}</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{ Form::open(array('method' => 'POST', 'class' => '', 'id' => 'ban-message-form', 'data-ban-form-id' => $user->id)) }}
                                                        <div class="form-group">
                                                            {{ Form::label('message', 'Причина бана') }}
                                                            {{ Form::textarea('message', null, ['class' => 'form-control', 'rows' => 3]) }}
                                                        </div>
                                                        {{ Form::close() }}
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-success ban-confirm" data-dismiss="modal" data-id="{{ $user->id }}">Забанить</button>
                                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
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
                            {{ SortingHelper::paginationLinks($users) }}
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
        // удаление пользователя
        $('button[name="destroy"]').on('click', function(e){
            var $form=$(this).closest('form');
            e.preventDefault();
            $('#confirm').modal({ backdrop: 'static', keyboard: false })
                    .one('click', '#delete', function() {
                        $form.trigger('submit'); // submit the form
                    });
        });

        // смена роли
        // показать кнопки после выбора роли
        $("form[id^='changeRole-form'] select").on('change', function(){
            $(this).parent().find('.buttons').html(
                '<button type="submit" class="btn btn-success btn-circle" name="changeRole"><i class="fa fa-check"></i></button>' +
                '<button type="button" class="btn btn-danger btn-circle" name="cancelChangeRole" data-role="' + $(this).data('role') + '"><i class="glyphicon glyphicon-remove"></i></button>'
            );
        });
        // отменить изменение роли
        $("form[id^='changeRole-form'] .buttons").on('click', 'button[name="cancelChangeRole"]', function() {
            $(this).parent().parent().find('select').val($(this).data('role'));
            $(this).parent().html('');
        });
        // сохранить новую роль
        $("form[id^='changeRole-form']").submit(function(event) {
            event.preventDefault ? event.preventDefault() : event.returnValue = false;
            var $form = $(this),
                    role = $form.find('select').val(),
                    url = $form.attr('action');
            var posting = $.post(url, { role: role });
            posting.done(function(data) {
                if(data.success) {
                    $form.find('.buttons').html('');
                    $form.find("select option[value='0']").remove();
                } //success
            }); //done
        });

        // забанить
        $('.buttons').on('click', '.ban', function(){
            var userId = $(this).data('id');

            $('[data-ban-modal-id='+ userId +']').modal({ backdrop: 'static', keyboard: false })
                .one('click', '.ban-confirm', function() {
                    var $form = $('[data-ban-form-id='+ $(this).data('id') +']'),
                        data = $form.serialize();
                    $.ajax({
                        url: '/admin/users/ban/' + userId,
                        dataType: "text json",
                        type: "POST",
                        data: {formData: data},
                        success: function(response) {
                            if(response.success){
                                $('#message').text(response.message);
                                var $userTr = $('[data-user-id='+ userId +']');
                                $userTr.addClass('danger');
                                $userTr.find('.banned-link').toggleClass('ban unban').html('<i class="fa fa-unlock"></i>');
                            } else {
                                $('#message').text(response.message);
                            }
                        }
                    });
                });
        });

        // разбанить
        $('.buttons').on('click', '.unban', function(){
            var userId = $(this).data('id');

            $('[data-unban-modal-id='+ userId +']').modal({ backdrop: 'static', keyboard: false })
                .one('click', '.unban-confirm', function() {
                    $.ajax({
                        url: '/admin/users/unban/' + userId,
                        dataType: "text json",
                        type: "POST",
                        data: {},
                        success: function(response) {
                            if(response.success){
                                $('#message').text(response.message);
                                var $userTr = $('[data-user-id='+ userId +']');
                                $userTr.removeClass('danger');
                                $userTr.find('.banned-link').toggleClass('ban unban').html('<i class="fa fa-lock"></i>');
                            } else {
                                $('#message').text(response.message);
                            }
                        }
                    });
                });
        });
    </script>
@stop