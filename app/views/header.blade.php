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
            <div class="col-md-5">
                <div id="site-title">
                    <h1>
                        Школа авторемонта
                        <br>
                        <span class="slogan">
                            Статьи, советы и рекомендации по ремонту и обслуживанию автомобилей своими руками
                        </span>
                    </h1>
                </div>
                <!--Search-->
                <div id="search">
                    {{ Form::open(['method' => 'GET', 'route' => ['search']], ['id' => 'search-form']) }}

                    <div class="col-md-10">
                        <div class="form-group">
                            {{ Form::input('search', 'query', null, ['class' => 'form-control', 'id' => 'query']) }}
                        </div>
                    </div>
                    <div class="col-md-2">
                        {{ Form::submit('Найти', ['class' => 'btn btn-success']) }}
                    </div>

                    {{ Form::close() }}
                </div>
            </div>
            <div class="col-md-3">
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
    </div>
</div>