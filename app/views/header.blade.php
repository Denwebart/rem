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
                @else
                    <div class="buttons pull-right">
                        <a href="{{ URL::route('logout') }}" class="btn btn-primary margin-top-20 pull-right btn-logout">
                            <span>
                                <span class="text hidden-xs">Выйти</span>
                                <i class="material-icons">exit_to_app</i>
                            </span>
                        </a>
                    </div>
                @endif
                {{ $menuWidget->topMenu }}
            </div>
            <div class="col-md-6 col-sm-2 col-xs-3">
                <div class="logo">
                    <a href="{{ URL::to('/') }}">
                        <?php $alt = isset($settings) ? $settings['siteTitle']['value'] . ' ' .$settings['siteSlogan']['value'] : ''; ?>
                        {{ HTML::image('images/logo.png', $alt, ['title' => $alt, 'class' => 'img-responsive hidden-sm hidden-xs']) }}
                        {{ HTML::image('images/logo-circle.png', $alt, ['title' => $alt, 'class' => 'img-responsive hidden-lg hidden-md']) }}
                    </a>
                </div>
            </div>
            <div class="col-md-6 col-sm-9 col-xs-9">
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
                        @else
                            <div class="buttons pull-right">
                                <a href="{{ URL::route('logout') }}" class="btn btn-primary margin-top-20 pull-right btn-logout">
                                    <span>
                                        <span class="text hidden-md">Выйти</span>
                                        <i class="material-icons">exit_to_app</i>
                                    </span>
                                </a>
                            </div>
                        @endif
                        {{ $menuWidget->topMenu }}
                    </div>
                </div>
                <div class="row">
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
    </div>
</div>