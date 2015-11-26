@if(Auth::check())
    @if(Auth::user()->isAdmin())
        <div class="buttons pull-right" style="display: none">
            <div class="access">
                {{ Advertising::$access[$item->access] }}
            </div>
            <a href="{{ URL::route('admin.advertising.edit', ['id' => $item->id, 'backUrl' => urlencode(Request::url())]) }}" class="" title="Редактировать этот рекламный блок/виджет" data-toggle="tooltip">
                <i class="material-icons">edit</i>
            </a>
            <!-- Отключить/выключить рекламный блок -->
            <a href="javascript:void(0)" class="change-active-status" data-id="{{ $item->id }}" data-is-active="{{ $item->is_active }}" title="{{ $item->is_active ? 'Выключить этот рекламный блок/виджет' : 'Включить этот рекламный блок/виджет' }}" data-toggle="tooltip">
                @if($item->is_active)
                    <i class="material-icons">visibility_off</i>
                @else
                    <i class="material-icons">visibility</i>
                @endif
            </a>
        </div>
        <div class="clearfix"></div>
    @endif
@endif