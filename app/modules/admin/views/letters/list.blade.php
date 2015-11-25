@if(count($letters))
    @foreach($letters as $letter)
        <tr{{ ($letter->read_at) ? '' : ' class="unread"' }}>
            <td class="small">{{ $letter->id }}</td>
            {{--<td class="small"><input type="checkbox" /></td>--}}
            {{--<td class="small"><i class="fa fa-star"></i></td>--}}
            <td>
                @if($letter->user)
                    <a href="{{ URL::route('user.profile', ['login' => $letter->user->getLoginForUrl()]) }}">
                        {{ $letter->user->getAvatar('mini', ['width' => '25']) }}
                    </a>
                @else
                    {{{ $letter->user_name }}}
                    ({{{ $letter->user_email }}})
                @endif
            </td>
            <td>
                @if($letter->ip)
                    {{ $letter->ip->ip }}
                @endif
            </td>
            <td class="subject">{{ $letter->subject }}</td>
            <td>
                {{ DateHelper::dateFormat($letter->created_at, false) }}
                <br>
                {{ date('H:i', strtotime($letter->created_at)) }}
            </td>
            <td>
                @if($letter->read_at)
                    {{ DateHelper::dateFormat($letter->read_at, false) }}
                    <br>
                    {{ date('H:i', strtotime($letter->read_at)) }}
                @else
                    -
                @endif
            </td>
            <td class="button-column two-buttons">
                <a class="btn btn-primary btn-sm margin-right-5" href="{{ URL::route('admin.letters.show', $letter->id) }}" title="Просмотреть письмо" data-toggle="tooltip" data-placement="left">
                    <i class="fa fa-search-plus"></i>
                </a>

                {{ Form::open(array('method' => 'POST', 'route' => array('admin.letters.markAsDeleted', $letter->id), 'class' => 'destroy as-button')) }}
                <button type="submit" class="btn btn-danger btn-sm" name="destroy" title="В корзину" data-toggle="tooltip" data-placement="left">
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
@else
    <tr>
        <td colspan="7">
            {{ $notFoundLetters }}
        </td>
    </tr>
@endif