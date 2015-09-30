<!--Search-->
<div id="search">
    <div class="row">
        <div class="col-md-12 col-md-offset-0 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
            {{ Form::open(['method' => 'GET', 'route' => ['search'], 'id' => 'search-form']) }}
            <div class="row">
                <div class="col-xs-11">
                    <div class="form-group">
                        {{ Form::input('search', 'query', null,
                            [
                                'class' => 'form-control floating-label',
                                'id' => 'query',
                                'placeholder' => 'Поиск по сайту',
                                'data-hint' => 'Введите фразу для поиска, например: "Замена антифриза"'
                            ]
                        ) }}
                    </div>
                </div>
                <div class="col-xs-1" style="padding: 0">
                    <button type="submit" style="width: 100%">
                        <i class="material-icons">search</i>
                        <div class="ripple-wrapper"></div>
                    </button>
                </div>
            </div>
            {{ Form::close() }}
            @section('script')
                @parent

                <script type="text/javascript">
                    // исключение пустых полей формы
                    $("#search-form").submit(function() {
                        if($("#query").val() == "") {
                            $("#query").prop("disabled", true);
                        }
                    });
                </script>
            @stop
        </div>
    </div>
</div>