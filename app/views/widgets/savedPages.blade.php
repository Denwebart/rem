<div id="saved-pages">
    @if(!Auth::user()->hasInSaved($page->id))
        <a href="javascript:void(0)" id="save-page" data-page-id="{{ $page->id }}">
            <i class="glyphicon glyphicon-floppy-save"></i>
        </a>
    @else
        <a href="javascript:void(0)" id="remove-page" data-page-id="{{ $page->id }}">
            <i class="glyphicon glyphicon-floppy-saved"></i>
        </a>
    @endif
</div>
<div id="save-page-message"></div>

@section('script')
    @parent

    <script type="text/javascript">
        $("#saved-pages").on('click', '#save-page', function() {
            var $link = $(this);
            var pageId = $link.data('pageId');
            $.ajax({
                url: "{{ URL::route('user.savePage', ['login' => Auth::user()->getLoginForUrl()]) }}",
                dataType: "text json",
                type: "POST",
                data: {pageId: pageId},
                success: function(response) {
                    if(response.success){
                        $("#save-page-message").text(response.message);
                        $link.find('i').attr('class', 'glyphicon glyphicon-floppy-saved');
                        $link.attr('id', 'remove-page');
                    } else {
                        $("#save-page-message").text(response.message);
                    }
                }
            });
        });

        $("#saved-pages").on('click', '#remove-page', function() {
            var $link = $(this);
            var pageId = $link.data('pageId');
            $.ajax({
                url: "{{ URL::route('user.removePage', ['login' => Auth::user()->getLoginForUrl()]) }}",
                dataType: "text json",
                type: "POST",
                data: {pageId: pageId},
                success: function(response) {
                    if(response.success){
                        $("#save-page-message").text(response.message);
                        $link.find('i').attr('class', 'glyphicon glyphicon-floppy-save');
                        $link.attr('id', 'save-page');
                    } else {
                        $("#save-page-message").text(response.message);
                    }
                }
            });
        });
    </script>
@endsection