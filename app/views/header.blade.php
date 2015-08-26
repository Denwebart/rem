<div class="header">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="logo">
                    <a href="{{ URL::to('/') }}">
                        {{ HTML::image('images/metal_logo_7.png') }}
                    </a>
                </div>
                <div class="logo-text">
                    <a href="{{ URL::to('/') }}">
                        {{ HTML::image('images/metal_text_4.png') }}
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        {{ $menuWidget->topMenu() }}
                        @if (!Auth::check())
                            <div class="buttons pull-left">
                                <a href="{{ URL::route('login') }}" class="btn btn-primary margin-top-20 pull-right btn-login">
                                    <span>
                                        <span class="text">Войти</span>
                                        <i class="material-icons">exit_to_app</i>
                                    </span>
                                </a>
                                <a href="{{ URL::route('register') }}" class="pull-right btn-register">
                                    Зарегистрироваться
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <!--Search-->
                        <div id="search">
                            {{ Form::open(['method' => 'GET', 'route' => ['search'], 'id' => 'search-form']) }}
                                <div class="row">
                                    <div class="col-md-11">
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
                                    <div class="col-md-1" style="padding: 0">
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
                            @endsection
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div id="site-title">
                    @if(isset($settings))
                        <h1>
                            {{ $settings['siteTitle']['value'] }}
                            <span class="slogan">
                                {{ $settings['siteSlogan']['value'] }}
                            </span>
                        </h1>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>