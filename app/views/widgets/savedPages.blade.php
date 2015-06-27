<div id="saved-pages">
    @if(!Auth::user()->hasInSaved($page->id))
        <a href="javascript:void(0)" id="save-page" data-page-id="{{ $page->id }}">
            <i class="glyphicon glyphicon-floppy-save"></i>
            Добавить в сохранённые
        </a>
    @else
        <a href="javascript:void(0)" id="remove-page" data-page-id="{{ $page->id }}">
            <i class="glyphicon glyphicon-floppy-remove"></i>
            Убрать из сохранённых
        </a>
    @endif
        <span class="whoSaved">Уже сохранили: <span>{{ count($page->whoSaved) }}</span></span>
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
                        $link.html('<i class="glyphicon glyphicon-floppy-remove"></i> Убрать из сохранённых');
                        $link.attr('id', 'remove-page');
                        $("#saved-pages .whoSaved").find('span').text(response.whoSaved);
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
                        $link.html('<i class="glyphicon glyphicon-floppy-save"></i> Добавить в сохранённые');
                        $link.attr('id', 'save-page');
                        $("#saved-pages .whoSaved").find('span').text(response.whoSaved);
                    } else {
                        $("#save-page-message").text(response.message);
                    }
                }
            });
        });
    </script>
@endsection