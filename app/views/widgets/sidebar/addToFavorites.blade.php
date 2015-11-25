<div class="sidebar-widget favorites-widget">
    <a href="javascript:void(0)" onclick="return addFavorite(this);" title="Добавить сайт в закладки браузера" data-toggle="tooltip" class="">
        <span class="text">
            <i class="material-icons">grade</i>
            <span>В закладки</span>
        </span>
    </a>
</div>
@section('script')
    @parent

    <script type="text/javascript">
        function addFavorite(a) {
//            var title = document.title;
//            var url = document.location;
            var title = "Школа авторемонта - Ремонт автомобиля своими руками";
            var url = "<?php echo Config::get('app.url')?>";
            try {
                // Internet Explorer
                window.external.AddFavorite(url, title);
            } catch (e) {
                try {
                    // Mozilla
                    window.sidebar.addPanel(title, url, "");
                } catch (e) {
                    // Opera
                    if (typeof(opera)=="object" || window.sidebar) {
                        a.rel="sidebar";
                        a.title=title;
                        a.url=url;
                        a.href=url;
                        return true;
                    } else {
                        // Unknown
                        var message = '@include('widgets.siteMessages.warning', ['siteMessage' => 'Нажмите Ctrl-D чтобы добавить страницу в закладки.'])';
                        $('#site-messages').prepend(message);
                    }
                }
            }
            return false;
        }
    </script>

@stop