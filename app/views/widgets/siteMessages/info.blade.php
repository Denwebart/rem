@if(isset($siteMessage))
    <div class="alert alert-dismissable alert-info">
        <button type="button" class="close" data-dismiss="alert">×</button>
        @if(isset($siteMessageTitle))
            <strong>{{ $siteMessageTitle }}</strong>
        @endif
        {{ $siteMessage }}
    </div>
@endif