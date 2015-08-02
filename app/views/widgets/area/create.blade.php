<div class="area-title" style="display: none">
    {{ Advertising::$areas[$area] }}
</div>
<div class="buttons" style="display: none">
    <a href="{{ URL::route('admin.advertising.create', ['backUrl' => urlencode(Request::url()), 'area' => $area]) }}" class="btn btn-success btn-sm create" title="Создать рекламный блок/виджет в этой области">
        <i class="material-icons">add</i>
    </a>
</div>