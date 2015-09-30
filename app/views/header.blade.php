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
                {{--@include('search')--}}
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