<div class="header">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-xs-12 hidden-md hidden-lg">
                @if (!Auth::check())
                    <div class="buttons pull-right">
                        <a href="{{ URL::route('login') }}" class="btn btn-primary margin-top-20 pull-right btn-login">
                            <span>
                                <span class="text hidden-xs">Войти</span>
                                <i class="material-icons">exit_to_app</i>
                            </span>
                        </a>
                        <a href="{{ URL::route('register') }}" class="pull-right btn-register">
                            Зарегистрироваться
                        </a>
                    </div>
                @endif
                {{ $menuWidget->topMenu() }}
            </div>
            <div class="col-md-6 col-logo">
                <div class="logo">
                    <a href="{{ URL::to('/') }}">
                        {{ HTML::image('images/logo.png', isset($settings) ? $settings['siteTitle']['value'] . ' ' .$settings['siteSlogan']['value'] : '', ['class' => 'img-responsive']) }}
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row hidden-sm hidden-xs">
                    <div class="col-md-12">
                        @if (!Auth::check())
                            <div class="buttons pull-right">
                                <a href="{{ URL::route('login') }}" class="btn btn-primary margin-top-20 pull-right btn-login">
                                    <span>
                                        <span class="text hidden-md">Войти</span>
                                        <i class="material-icons">exit_to_app</i>
                                    </span>
                                </a>
                                <a href="{{ URL::route('register') }}" class="pull-right btn-register">
                                    Зарегистрироваться
                                </a>
                            </div>
                        @endif
                        {{ $menuWidget->topMenu() }}
                    </div>
                </div>
                <div class="row">
                    <!--Search-->
                    <div id="search">
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
                            @endsection
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div id="site-title">
                    @if(isset($settings))
                        <h1>
                            <span>
                                {{ $settings['siteTitle']['value'] }}
                            </span>
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