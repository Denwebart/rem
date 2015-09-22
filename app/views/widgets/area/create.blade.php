<div class="area-title" style="display: none">
    {{ Advertising::$areas[$area] }}
</div>
<div class="buttons" style="display: none">
    <a href="{{ URL::route('admin.advertising.create', ['backUrl' => urlencode(Request::url()), 'area' => $area]) }}" class="create" title="Создать рекламный блок/виджет в этой области" data-toggle="tooltip">
        <i class="material-icons mdi-success">add</i>
    </a>
</div>