<div class="area-title" style="display: none">
    {{ Advertising::$areas[$area] }}
</div>
<div class="buttons" style="display: none">
    <a href="{{ URL::route('admin.advertising.create', ['backUrl' => urlencode(Request::url()), 'area' => $area]) }}" class="btn btn-success btn-sm">
        <span class="mdi-content-add"></span>
    </a>
</div>