@foreach($users as $user)
    <tr data-user-id="{{ $user->id }}" @if($user->is_banned) class="danger" @elseif($user->created_at > $headerWidget->getLastActivity()) class="info" @endif>
        <td>{{ $user->id }}</td>
        <td>
            <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                {{ $user->getAvatar('mini') }}
            </a>
        </td>
        <td>
            @if($user->isAdmin() && (1 == $user->id || Auth::user()->id == $user->id))
                {{ User::$roles[$user->role] }}
            @else
                {{ Form::open([
                    'action' => ['AdminUsersController@changeRole', $user->id],
                    'id' => 'changeRole-form-' . $user->id,
                ]) }}

                {{ Form::select('role', ($user->hasRole()) ? User::$roles : [User::ROLE_NONE => 'Не назначена'] + User::$roles, $user->role, ['data-role' => $user->role]) }}

                <div class="buttons pull-right"></div>
                {{ Form::hidden('_token', csrf_token()) }}
                {{ Form::close() }}
            @endif
        </td>
        <td>{{ $user->login }}</td>
        <td>{{ $user->getFullName() }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->points }}</td>
        <td class="status">
            @if($user->is_active)
                <span class="published" title="Активный" data-toggle="tooltip"></span>
            @else
                <span class="not-published" title="Неактивный" data-toggle="tooltip"></span>
            @endif
        </td>
        <td class="date">
            {{ DateHelper::dateFormat($user->created_at, false) }}
            <br>
            {{ date('H:i', strtotime($user->created_at)) }}
        </td>
        <td class="honors">
            @if(count($user->userHonors))
                @foreach($user->userHonors as $key => $userHonor)
                    @if($key < 3)
                        <a href="{{ URL::route('honor.info', ['alias' => $userHonor->honor->alias]) }}">
                            {{ $userHonor->honor->getImage(null, [
                                'width' => '25',
                                'title' => !is_null($userHonor->comment)
                                    ? $userHonor->honor->title . ' ('. $userHonor->comment .')'
                                    : $userHonor->honor->title,
                                'alt' => $userHonor->honor->title,
                                'data-toggle' => 'tooltip'])
                            }}
                        </a>
                    @else
                        <br>
                        <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}#honors" title="Посмотреть все награды" data-toggle="tooltip">+ еще {{ count($user->userHonors) - 3 }}</a>
                        <?php break; ?>
                    @endif
                @endforeach
            @else
                Нет
            @endif
        </td>
        <td class="button-column three-buttons">
            <a class="btn btn-info btn-sm margin-right-5" href="{{ URL::route('user.edit', ['login' => $user->getLoginForUrl(), 'backUrl' => isset($url) ? urlencode($url) : urlencode(Request::fullUrl())]) }}" title="Редактировать">
                <i class="fa fa-edit"></i>
            </a>
            @if(!$user->isAdmin())
                <!-- Бан пользователя -->
                @if(!$user->isAdmin())
                    @if(!$user->is_banned)
                        <a class="btn btn-primary btn-sm margin-right-5 banned-link ban" href="javascript:void(0)" title="Забанить" data-id="{{ $user->id }}" data-toggle="tooltip">
                            <i class="fa fa-lock"></i>
                        </a>
                    @else
                        <a class="btn btn-primary btn-sm margin-right-5 banned-link unban" href="javascript:void(0)" title="Разбанить" data-id="{{ $user->id }}" data-toggle="tooltip">
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
                                {{ Form::hidden('_token', csrf_token()) }}
                                {{ Form::close() }}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success ban-confirm" data-dismiss="modal" data-id="{{ $user->id }}">Забанить</button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->

                <!-- Удалить -->
                {{ Form::open(['method' => 'DELETE', 'url' => URL::route('admin.users.destroy', ['id' => $user->id, 'backUrl' => isset($url) ? urlencode($url) : urlencode(Request::fullUrl())]), 'class' => 'as-button']) }}
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
            @endif
        </td>
    </tr>
@endforeach