@extends('admin::layouts.admin')

@section('content')
    <div class="page-head">
        <h1>Пользователи  <small>все пользователи сайта</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active">Пользователи</li>
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
                                <th>ID</th>
                                <th>Фото</th>
                                <th>Роль</th>
                                <th>Логин</th>
                                <th>Имя</th>
                                <th>Email</th>
                                <th>Статус</th>
                                <th>Дата регистрации</th>
                                <th class="button-column">
                                    <a class="btn btn-success btn-sm" href="{{ URL::route('admin.users.create') }}">
                                        <i class="fa fa-plus "></i> Создать
                                    </a>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        <a href="{{ URL::route('user.profile', ['login' => $user->login]) }}">
                                            {{ $user->getAvatar('mini') }}
                                        </a>
                                    </td>
                                    <td>
                                        @if($user->isAdmin())
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
                                    <td>
                                        @if($user->is_active)
                                            <span class="label label-success">Активный</span>
                                        @else
                                            <span class="label label-warning">Неактивный</span>
                                        @endif
                                    </td>
                                    <td>{{ DateHelper::dateFormat($user->created_at) }}</td>
                                    <td>
                                        {{--<a class="btn btn-success btn-sm" href="{{ URL::route('admin.users.show', $user->id) }}">--}}
                                        {{--<i class="fa fa-search-plus "></i>--}}
                                        {{--</a>--}}
                                        <a class="btn btn-info btn-sm" href="{{ URL::route('admin.users.edit', $user->id) }}">
                                            <i class="fa fa-edit "></i>
                                        </a>
                                        {{ Form::open(array('method' => 'DELETE', 'route' => array('admin.users.destroy', $user->id), 'class' => 'as-button')) }}
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
                            {{ $users->links() }}
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
    </script>
@stop