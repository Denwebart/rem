<div class="header">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div id="logo">
                    <a href="{{ URL::to('/') }}">
                        {{ HTML::image('images/logo.png') }}
                    </a>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-7">
                        <div id="site-title">
                            <h1>
                                Школа авторемонта
                                <br>
                        <span class="slogan">
                            Статьи, советы и рекомендации по ремонту и обслуживанию автомобилей своими руками
                        </span>
                            </h1>
                        </div>
                    </div>
                    <div class="col-md-5">
                        {{ $menuWidget->topMenu() }}
                        @if (!Auth::check())
                            <a href="{{ URL::to('users/login') }}" class="btn btn-primary margin-top-20 pull-right btn-sm btn-login">
                                Войти
                                <i class="glyphicon glyphicon-log-in"></i>
                            </a>
                            <br>
                            <a href="{{ URL::to('users/register') }}" class="pull-right btn-register">
                                Зарегистрироваться
                            </a>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-8">
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
                                                'placeholder' => 'Поиск',
                                                'data-hint' => 'Введите фразу для поиска, например: "Замена антифриза"'
                                            ]
                                        ) }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-default btn-raised" style="width: 100%">
                                        <i class="mdi-action-search"></i>
                                        <span>Найти</span>
                                        <div class="ripple-wrapper"></div>
                                    </button>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>