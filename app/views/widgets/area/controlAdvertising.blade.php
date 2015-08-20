<div class="buttons pull-right" style="display: none">
    <div class="access">
        {{ Advertising::$access[$item->access] }}
    </div>
    <a href="{{ URL::route('admin.advertising.edit', ['id' => $item->id, 'backUrl' => urlencode(Request::url())]) }}" class="btn btn-info btn-sm" title="Редактировать этот рекламный блок/виджет">
        <i class="material-icons">edit</i>
    </a>
    <!-- Отключить/выключить рекламный блок -->
    <a href="javascript:void(0)" class="btn btn-warning btn-sm change-active-status" data-id="{{ $item->id }}" data-is-active="{{ $item->is_active }}" title="{{ $item->is_active ? 'Выключить этот рекламный блок/виджет' : 'Включить этот рекламный блок/виджет' }}">
        @if($item->is_active)
            <i class="material-icons">visibility_off</i>
        @else
            <i class="material-icons">visibility</i>
        @endif
    </a>
</div>
<div class="clearfix"></div>
<div class="advertising-title" style="display: none">
    <a href="{{ URL::route('admin.advertising.index', ['id' => $item->id]) }}" title="Смотреть в админке">
        {{ $item->title }}
    </a>
</div>