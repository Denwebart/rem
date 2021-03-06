@if(!$user->isAdmin())
    <div class="banned buttons pull-left" data-ban-button-id="{{ $user->id }}">
        @if(!$user->is_banned)
            <a href="javascript:void(0)" class="banned-link ban" data-id="{{ $user->id }}" title="Забанить" data-toggle="tooltip" data-placement="bottom">
                <i class="material-icons">lock</i>
            </a>
        @else
            <a href="javascript:void(0)" class="banned-link unban" data-id="{{ $user->id }}" title="Разбанить" data-toggle="tooltip" data-placement="bottom">
                <i class="material-icons">lock_open</i>
            </a>
        @endif
    </div>

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
@endif

@section('script')
    @parent

    @if(!$user->isAdmin())
        <script type="text/javascript">
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
                            beforeSend: function(request) {
                                return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                            },
                            success: function(response) {
                                if(response.success){
                                    $('#site-messages').prepend(response.message);
                                    setTimeout(function() {
                                        hideSiteMessage($('.site-message'));
                                    }, 2000);

                                    $('[data-ban-button-id='+ userId +']').find('.banned-link')
                                            .toggleClass('ban unban')
                                            .html('<i class="material-icons">lock_open</i>')
                                            .attr('title', 'Разбанить')
                                            .attr('data-toggle', 'tooltip')
                                            .attr('data-placement', 'bottom');
                                    $('.profile-user-avatar .avatar-link').append(response.bannedImage);
                                } else {
                                    $('#site-messages').prepend(response.message);
                                    setTimeout(function() {
                                        hideSiteMessage($('.site-message'));
                                    }, 2000);
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
                            beforeSend: function(request) {
                                return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                            },
                            success: function(response) {
                                if(response.success){
                                    $('#site-messages').prepend(response.message);
                                    setTimeout(function() {
                                        hideSiteMessage($('.site-message'));
                                    }, 2000);

                                    $('[data-ban-button-id='+ userId +']').find('.banned-link')
                                            .toggleClass('ban unban')
                                            .html('<i class="material-icons">lock</i>')
                                            .attr('title', 'Забанить')
                                            .attr('data-toggle', 'tooltip')
                                            .attr('data-placement', 'bottom');
                                    $('.profile-user-avatar .avatar-link').find('.banned-image').remove();
                                } else {
                                    $('#site-messages').prepend(response.message);
                                    setTimeout(function() {
                                        hideSiteMessage($('.site-message'));
                                    }, 2000);
                                }
                            }
                        });
                    });
            });
        </script>
    @endif
@stop
