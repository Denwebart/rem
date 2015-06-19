@extends('admin::layouts.admin')

@section('content')
    <div class="page-head">
        <h1>Забаненные пользователи  <small>забаненные пользователи сайта</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active"><a href="{{ URL::route('admin.users.index') }}">Пользователи</a></li>
            <li class="active">Забаненные пользователи</li>
        </ol>
    </div>

    <div class="content">
        <!-- Main row -->
        <div class="row">

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
                                    Награды
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.index', 'Сколько раз забанен', '') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.index', 'Забанен', '') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.index', 'Разбанен', '') }}
                                </th>
                                <th>
                                    {{ SortingHelper::sortingLink('admin.users.index', 'Причина бана', '') }}
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
                                <tr data-user-id="{{ $user->id }}">
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        <a href="{{ URL::route('user.profile', ['login' => $user->login]) }}">
                                            {{ $user->getAvatar('mini') }}
                                        </a>
                                    </td>
                                    <td>
                                       {{ User::$roles[$user->role] }}
                                    </td>
                                    <td>{{ $user->login }}</td>
                                    <td>{{ $user->getFullName() }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->points }}</td>
                                    <td>
                                        @foreach($user->honors as $honor)
                                            <a href="{{ URL::route('admin.honors.show', ['id' => $honor->id]) }}">
                                                {{ $honor->getImage(null, ['width' => '25px']) }}
                                            </a>
                                        @endforeach
                                    </td>
                                    <td>
                                        {{ count($user->banNotifications) }}
                                    </td>
                                    <td>
                                        <ul>
                                            @foreach($user->banNotifications as $key => $value)
                                                <li>
                                                    {{ DateHelper::dateFormat($value->ban_at) }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            @foreach($user->banNotifications as $key => $value)
                                                <li>
                                                    {{ DateHelper::dateFormat($value->unban_at) }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            @foreach($user->banNotifications as $key => $value)
                                                <li>
                                                    {{$value->message }}
                                                </li>
                                            @endforeach
                                        </ul>
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

                                        <!-- Снятие бана с пользователя -->
                                        <a class="btn btn-primary btn-sm banned-link unban" href="javascript:void(0)" title="Разбанить" data-id="{{ $user->id }}">
                                            <i class="fa fa-lock"></i>
                                        </a>

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

        // разбанить
        $('.unban').on('click', function(){
            var userId = $(this).data('id');
            $.ajax({
                url: '/admin/users/unban/' + userId,
                dataType: "text json",
                type: "POST",
                data: {},
                success: function(response) {
                    if(response.success){
                        $('[data-user-id='+ userId +']').remove();
                        $('#message').text(response.message);
                    } else {
                        $('#message').text(response.message);
                    }
                }
            });
        });
    </script>
@stop