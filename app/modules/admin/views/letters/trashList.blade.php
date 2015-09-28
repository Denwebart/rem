@if(count($letters))
    @foreach($letters as $letter)
        <tr{{ ($letter->read_at) ? '' : ' class="unread"' }}>
            <td class="small">{{ $letter->id }}</td>
            {{--<td class="small"><input type="checkbox" /></td>--}}
            {{--<td class="small"><i class="fa fa-star"></i></td>--}}
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
            <td class="time">{{ DateHelper::dateFormat($letter->deleted_at) }}</td>
            <td>
                <a class="btn btn-primary btn-sm" href="{{ URL::route('admin.letters.show', $letter->id) }}">
                    <i class="fa fa-search-plus "></i>
                </a>
                {{ Form::open(array('method' => 'POST', 'route' => array('admin.letters.markAsNew', $letter->id), 'class' => 'as-button')) }}
                <button type="submit" class="btn btn-success btn-sm">
                    <i class='fa fa-reply'></i>
                </button>
                {{ Form::hidden('_token', csrf_token()) }}
                {{ Form::close() }}
                {{ Form::open(array('method' => 'DELETE', 'route' => array('admin.letters.destroy', $letter->id), 'class' => 'destroy as-button')) }}
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
                                <p>Вы уверены, что хотите окончательно удалить письмо?</p>
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
@else
    <tr>
        <td colspan="7">
            {{ $notFoundLetters }}
        </td>
    </tr>
@endif