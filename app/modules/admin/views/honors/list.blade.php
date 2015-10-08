@foreach($honors as $honor)
    <tr>
        <td>{{ $honor->id }}</td>
        <td>
            <a href="{{ URL::route('admin.honors.show', ['id' => $honor->id]) }}">
                {{ $honor->getImage(null, ['width' => '50px']) }}
            </a>
        </td>
        <td>{{ $honor->title }}</td>
        <td>{{ $honor->alias }}</td>
        <td>{{ $honor->description }}</td>
        <td class="users">
            @foreach($honor->users as $key => $user)
                <div class="user pull-left margin-right-10">
                    <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                        {{ $user->getAvatar('mini', ['width' => '25px']) }}
                    </a>
                    @if($user->awardsNumber > 1)
                        <span>
                            x {{ $user->awardsNumber }}
                        </span>
                    @endif
                </div>
            @endforeach
        </td>
        <td class="button-column one-button">
            <a class="btn btn-info btn-sm margin-right-5" href="{{ URL::route('admin.honors.edit', ['id' => $honor->id, 'backUrl' => isset($url) ? urlencode($url) : urlencode(Request::fullUrl())]) }}">
                <i class="fa fa-edit "></i>
            </a>

            @if(Auth::user()->isAdmin() && is_null($honor->key))
                {{ Form::open(['method' => 'DELETE', 'url' => URL::route('admin.honors.destroy', ['id' => $honor->id, 'backUrl' => isset($url) ? urlencode($url) : urlencode(Request::fullUrl())]), 'class' => 'as-button']) }}
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
                                <button type="button" class="btn btn-success delete" data-dismiss="modal">Да</button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Нет</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
            @endif
        </td>
    </tr>
@endforeach