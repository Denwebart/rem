@if(Auth::check())
    <div id="saved-pages" class="saved pull-left">
        @if(!Auth::user()->hasInSaved($page->id))
            <a href="javascript:void(0)" id="save-page" data-page-id="{{ $page->id }}" title='Если вам понравилась статья, вы можете добавить ее в "Сохраненное"'>
                <i class="material-icons">archive</i>
                <span>Сохранить себе</span>
            </a>
        @else
            <a href="javascript:void(0)" id="remove-page" data-page-id="{{ $page->id }}" title='Убрать статью из сохраненного'>
                <i class="material-icons">archive</i>
                <span>Убрать</span>
            </a>
        @endif
        <span class="whoSaved" title="Сколько пользователей сохранили">
            (<span>{{ count($page->whoSaved) }})</span>
        </span>
        <div id="save-page-message"></div>
    </div>

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
                    beforeSend: function(request) {
                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                    },
                    success: function(response) {
                        if(response.success){
                            $("#save-page-message").html(response.message);
                            $link.html('<i class="material-icons">archive</i><span>Убрать</span>');
                            $link.attr('id', 'remove-page');
                            $link.attr('title', 'Убрать статью из сохраненного');
                            $("#saved-pages .whoSaved").find('span').text(response.whoSaved);
                        } else {
                            $("#save-page-message").html(response.message);
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
                    beforeSend: function(request) {
                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                    },
                    success: function(response) {
                        if(response.success){
                            $("#save-page-message").html(response.message);
                            $link.html('<i class="material-icons">archive</i><span>Сохранить себе</span>');
                            $link.attr('id', 'save-page');
                            $link.attr('title', 'Если вам понравилась статья, вы можете добавить ее в "Сохраненное"');
                            $("#saved-pages .whoSaved").find('span').text(response.whoSaved);
                        } else {
                            $("#save-page-message").html(response.message);
                        }
                    }
                });
            });
        </script>
    @endsection
@else
    <div class="saved-count pull-left" title="Сколько пользователей сохранили">
        <i class="material-icons">archive</i>
        <span>{{ count($page->whoSaved) }}</span>
    </div>
@endif