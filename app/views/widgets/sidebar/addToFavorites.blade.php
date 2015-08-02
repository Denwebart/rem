<div id="favorites-widget" class="sidebar-widget">
    <a href="javascript:void(0)" onclick="return addFavorite(this);" class="btn btn-default btn-raised">
        <i class="material-icons">grade</i>
        <span>В закладки</span>
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
                        alert('Нажмите Ctrl-D чтобы добавить страницу в закладки');
                    }
                }
            }
            return false;
        }
    </script>

@endsection