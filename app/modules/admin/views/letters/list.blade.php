@if(count($letters))
    @foreach($letters as $letter)
        <div class="letter {{ ($letter->read_at) ? '' : ' unread' }}" data-letter-id="{{ $letter->id }}">
            <div class="checkbox">
                <input type="checkbox" />
            </div>
            <div class="content">
                <div class="date date-created" data-toggle="tooltip" data-placement="left" title="{{ DateHelper::dateFormat($letter->created_at, false) }}">
                    {{ DateHelper::getRelativeTime($letter->created_at) }}
                </div>
                <div class="user">
                    @if($letter->user)
                        <a href="{{ URL::route('user.profile', ['login' => $letter->user->getLoginForUrl()]) }}" target="_blank">
                            {{ $letter->user->getAvatar('mini', ['width' => '25', 'data-placement' => 'right']) }}
                        </a>
                        {{{ $letter->user->login }}}
                        <span class="email">
                        ({{{ $letter->user->email }}})
                        </span>
                    @else
                        {{{ $letter->user_name }}}
                        <span class="email">
                        ({{{ $letter->user_email }}})
                        </span>
                    @endif
                </div>
                <div class="clearfix"></div>
                <div class="subject">
                    {{ $letter->subject }}
                </div>
            </div>

            {{--<td class="button-column two-buttons">--}}
                {{--<a class="btn btn-primary btn-sm margin-right-5" href="{{ URL::route('admin.letters.show', $letter->id) }}" title="Просмотреть письмо" data-toggle="tooltip" data-placement="left">--}}
                    {{--<i class="fa fa-search-plus"></i>--}}
                {{--</a>--}}

                {{--{{ Form::open(array('method' => 'POST', 'route' => array('admin.letters.markAsDeleted', $letter->id), 'class' => 'destroy as-button')) }}--}}
                {{--<button type="submit" class="btn btn-danger btn-sm" name="destroy" title="В корзину" data-toggle="tooltip" data-placement="left">--}}
                    {{--<i class='fa fa-trash-o'></i>--}}
                {{--</button>--}}
                {{--{{ Form::hidden('_token', csrf_token()) }}--}}
                {{--{{ Form::close() }}--}}

                {{--<div id="confirm" class="modal fade">--}}
                    {{--<div class="modal-dialog">--}}
                        {{--<div class="modal-content">--}}
                            {{--<div class="modal-header">--}}
                                {{--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>--}}
                                {{--<h4 class="modal-title">Удаление</h4>--}}
                            {{--</div>--}}
                            {{--<div class="modal-body">--}}
                                {{--<p>Вы уверены, что хотите переместить письмо в корзину?</p>--}}
                            {{--</div>--}}
                            {{--<div class="modal-footer">--}}
                                {{--<button type="button" class="btn btn-success" data-dismiss="modal" id="delete">Да</button>--}}
                                {{--<button type="button" class="btn btn-primary" data-dismiss="modal">Нет</button>--}}
                            {{--</div>--}}
                        {{--</div><!-- /.modal-content -->--}}
                    {{--</div><!-- /.modal-dialog -->--}}
                {{--</div><!-- /.modal -->--}}
            {{--</td>--}}
        </div>
    @endforeach
@else
    {{ $notFoundLetters }}
@endif