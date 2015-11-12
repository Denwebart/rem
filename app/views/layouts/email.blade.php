<?php
    $userIsRegistered = isset($userIsRegistered) ? $userIsRegistered : false;
?>
<!-- HEADER -->
<table width="100%" bgcolor="#eeeeee">
    <tr>
        <td></td>
        <td>
            <a href="{{ Config::get('app.url') }}">
                {{ HTML::image('/images/logo.png', '', ['width' => '200px']) }}
            </a>
        </td>
        <td></td>
    </tr>
</table><!-- /HEADER -->

<!-- BODY -->
<table width="100%" bgcolor="#eeeeee">
    <tr>
        <td></td>
        <td>
            <table>
                <tr>
                    <td>
                        @if(isset($content))
                            {{ $content }}
                        @endif
                        @yield('content')
                    </td>
                </tr>
            </table>
        </td>
        <td></td>
    </tr>
</table><!-- /BODY -->

<!-- FOOTER -->
<table width="100%" height="100px" background="{{ URL::to('/images/footer.jpg') }}">
    <tr>
        <td></td>
        <td style="width: 100%; text-align: center">
            <p style="font-size:12px; color:white; font-face:Arial;">
                {{ HTML::image('/images/logo-circle-footer.png', '', ['width' => '30px', 'style' => 'margin-right:7px;']) }}
                <a style="color:#03A9F4" href="{{ URL::to(Config::get('app.url')) }}">Avtorem.info</a>
                <span>2010 - 2015</span>
            </p>
            @if($userIsRegistered)
                <p style="font-size:11px; color:#cccccc; font-face:Arial;">
                    Вы получили это письмо, так как являетесь зарегистрированным пользователем сайта
                    <a style="color:#03A9F4"href="{{ URL::to(Config::get('app.url')) }}">avtorem.info</a>.
                    Настроить рассылку писем вы можете в разделе "Настройки" своего профиля.
                </p>
            @endif
            {{--<p>--}}
            {{--<a href="#">Terms</a> |--}}
            {{--<a href="#">Privacy</a> |--}}
            {{--<a href="#"><unsubscribe>Unsubscribe</unsubscribe></a>--}}
            {{--</p>--}}
        </td>
        <td></td>
    </tr>
</table><!-- /FOOTER -->