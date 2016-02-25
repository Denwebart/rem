<div class="sidebar-widget favorites-widget">
    <a href="javascript:void(0)" title="Добавить сайт в закладки браузера" data-toggle="tooltip" class="add-to-favorites">
        <span class="text">
            <i class="material-icons">grade</i>
            <span>В закладки</span>
        </span>
    </a>
</div>
@section('script')
    @parent

    <script type="text/javascript">

        $('.add-to-favorites').on('click', function(e) {
            var bookmarkURL = window.location.href;
            var bookmarkTitle = document.title;

            //var bookmarkTitle = "Школа авторемонта - Ремонт автомобиля своими руками";
            //var bookmarkURL = "<?php echo Config::get('app.url')?>";

            if ('addToHomescreen' in window && window.addToHomescreen.isCompatible) {
                // Mobile browsers
                addToHomescreen({ autostart: false, startDelay: 0 }).show(true);
            } else if (window.sidebar && window.sidebar.addPanel) {
                // Firefox version < 23
                window.sidebar.addPanel(bookmarkTitle, bookmarkURL, '');
            } else if ((window.sidebar && /Firefox/i.test(navigator.userAgent)) || (window.opera && window.print)) {
                // Firefox version >= 23 and Opera Hotlist
                $(this).attr({
                    href: bookmarkURL,
                    title: bookmarkTitle,
                    rel: 'sidebar'
                }).off(e);
                return true;
            } else if (window.external && ('AddFavorite' in window.external)) {
                // IE Favorite
                window.external.AddFavorite(bookmarkURL, bookmarkTitle);
            } else {
                // Other browsers (mainly WebKit - Chrome/Safari)
                alert('Нажмите ' + (/Mac/i.test(navigator.userAgent) ? 'Cmd' : 'Ctrl') + '+D чтобы добавить эту страницу в закладки.');
            }

            return false;
        });

    </script>
@stop

