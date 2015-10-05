<tr data-ip-id="{{ $ip->id }}">
    <td>{{ $ip->ip }}</td>
    <td>
        {{ DateHelper::dateFormat($ip->ban_at) }}
    </td>
    <td class="users">
        @foreach($ip->users as $key => $user)
            <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}" class="pull-left margin-right-10">
                {{ $user->getAvatar('mini', ['width' => '25px']) }}
            </a>
        @endforeach
    </td>
    <td>
        <a href="{{ URL::route('admin.comments.index', ['query' => $ip->ip]) }}" title="Все комментарии с этого ip">
            {{ count($ip->comments) }}
        </a>
    </td>
    <td>
        <a href="{{ URL::route('admin.letters.index', ['query' => $ip->ip]) }}" title="Все письма с этого ip">
            {{ count($ip->letters) }}
        </a>
    </td>
    <td class="button-column one-button">
        <!-- Снятие бана с ip-адреса -->
        <a class="btn btn-primary btn-sm banned-link unban" href="javascript:void(0)" title="Разбанить" data-id="{{ $ip->id }}" data-toggle="tooltip">
            <i class="fa fa-unlock"></i>
        </a>
        <div class="modal fade unban-modal" data-unban-modal-id="{{ $ip->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Снятие бана с ip-адреса</h4>
                    </div>
                    <div class="modal-body">
                        <p>Вы уверены, что хотите разбанить ip-адрес {{ $ip->ip }}?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success unban-confirm" data-dismiss="modal" data-id="{{ $ip->id }}">Разбанить</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </td>
</tr>