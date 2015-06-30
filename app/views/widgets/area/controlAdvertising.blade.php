<div class="buttons pull-right" style="display: none">
    <div class="access">
        {{ Advertising::$access[$item->access] }}
    </div>
    <a href="{{ URL::route('admin.advertising.edit', ['id' => $item->id, 'backUrl' => urlencode(Request::url())]) }}" class="btn btn-info btn-sm">
        <span class="mdi-editor-mode-edit"></span>
    </a>
    <!-- Отключить/выключить рекламный блок -->
    <a href="javascript:void(0)" class="btn btn-warning btn-sm change-active-status" data-id="{{ $item->id }}" data-is-active="{{ $item->is_active }}" title="{{ $item->is_active ? 'Отключить этот рекламный блок на этой старинце.' : 'Выключить этот рекламный блок на этой старинце.' }}">
        @if($item->is_active)
            <span class="mdi-action-visibility-off"></span>
        @else
            <span class="mdi-action-visibility"></span>
        @endif
    </a>
</div>
<div class="clearfix"></div>