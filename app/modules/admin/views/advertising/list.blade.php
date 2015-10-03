@foreach($advertising as $item)
    <tr class="widget">
        <td>{{ $item->id }}</td>
        <td>{{ Advertising::$types[$item->type] }}</td>
        <td>{{ Advertising::$areas[$item->area] }}</td>
        <td>{{ $item->position }}</td>
        <td>{{ $item->title }}</td>
        <td>
            @if(Advertising::TYPE_ADVERTISING == $item->type)
                {{ $item->description }}
            @else
                @if($item->code)
                    {{ Advertising::$widgets[$item->code] }} (кол-во: {{ $item->limit }})
                @else
                    <p>Виджет не выбран.</p>
                @endif
                @if($item->description)
                    <hr style="margin: 0">
                    {{ $item->description }}
                @endif
            @endif
        </td>
        <td>{{ Advertising::$access[$item->access] }}</td>
        <td>
            <!-- Отключить/выключить рекламный блок -->
            <a href="javascript:void(0)" class="change-active-status" data-id="{{ $item->id }}" data-is-active="{{ $item->is_active }}" title="{{ $item->is_active ? 'Выключить этот рекламный блок.' : 'Включить этот рекламный блок.' }}">
                @if($item->is_active)
                    <span class="label label-success">Включен</span>
                @else
                    <span class="label label-warning">Выключен</span>
                @endif
            </a>
        </td>
        <td>
            <a class="btn btn-info btn-sm" href="{{ URL::route('admin.advertising.edit', $item->id) }}">
                <i class="fa fa-edit "></i>
            </a>

            {{ Form::open(array('method' => 'DELETE', 'route' => array('admin.advertising.destroy', $item->id), 'class' => 'as-button')) }}
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
                            <button type="button" class="btn btn-success" data-dismiss="modal" id="delete">Да</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Нет</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </td>
    </tr>
@endforeach