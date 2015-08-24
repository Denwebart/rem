<div class="header">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                {{--<div id="logo">--}}
                    {{--<a href="{{ URL::to('/') }}">--}}
                        {{--{{ HTML::image('images/logo.png') }}--}}
                    {{--</a>--}}
                {{--</div>--}}

                {{--<div id="site-title">--}}
                    {{--<h1>--}}
                        {{--@if(isset($settings))--}}
                            {{--{{ $settings['siteTitle']['value'] }}--}}
                        {{--@endif--}}
                        {{--<br>--}}
                        {{--<span class="slogan">--}}
                        {{--@if(isset($settings))--}}
                            {{--{{ $settings['siteSlogan']['value'] }}--}}
                        {{--@endif--}}
                        {{--</span>--}}
                    {{--</h1>--}}
                {{--</div>--}}

                <div class="logo">
                    {{ HTML::image('images/metal_logo_7.png') }}
                </div>
                <div class="logo-text">
                    {{ HTML::image('images/metal_text_4.png') }}
                </div>
            </div>
            <div class="col-md-5">
                <div class="row">
                    <div class="col-md-12">
                        {{ $menuWidget->topMenu() }}
                        @if (!Auth::check())
                            <a href="{{ URL::route('login') }}" class="btn btn-primary margin-top-20 pull-right btn-login">
                                <span>
                                    <span class="text">Войти</span>
                                    <i class="material-icons">exit_to_app</i>
                                </span>
                            </a>
                            <br>
                            <a href="{{ URL::route('register') }}" class="pull-right btn-register">
                                Зарегистрироваться
                            </a>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <!--Search-->
                        <div id="search">
                            {{ Form::open(['method' => 'GET', 'route' => ['search']], ['id' => 'search-form']) }}
                                <div class="row">
                                    <div class="col-md-9">
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
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-default btn-raised" style="width: 100%">
                                            <i class="material-icons">search</i>
                                            <span>Найти</span>
                                            <div class="ripple-wrapper"></div>
                                        </button>
                                    </div>
                                </div>
                                {{ Form::hidden('_token', csrf_token()) }}
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>