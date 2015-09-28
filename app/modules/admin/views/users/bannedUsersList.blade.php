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

            <!-- Снятие бана с пользователя -->
            <a class="btn btn-primary btn-sm banned-link unban" href="javascript:void(0)" title="Разбанить" data-id="{{ $user->id }}">
                <i class="fa fa-unlock"></i>
            </a>

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

        </td>
    </tr>
@endforeach