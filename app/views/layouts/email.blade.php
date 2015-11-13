<?php
    $userModel = isset($userModel) ? $userModel : false;
    $getRegistered = isset($getRegistered) ? $getRegistered : false;
?>
<table width="700" align="center">
    <tbody>
        <tr>
            <td>
                <!-- HEADER -->
                <table width="100%" align="center">
                    <tr>
                        <td width="10"></td>
                        <td>
                            <a href="{{ Config::get('app.url') }}">
                                {{ HTML::image('/images/logo.png', '', ['width' => '450px']) }}
                            </a>
                        </td>
                        <td width="10"></td>
                    </tr>
                </table><!-- /HEADER -->
            </td>
        </tr>
        <tr>
            <td>
                <!-- BODY -->
                <table width="100%" align="center" bgcolor="#FAFAFA" style="min-height:200px;border:1px solid #F0F0F0;border-bottom:1px solid #C0C0C0;border-bottom:0;">
                    <tr>
                        <td width="20"></td>
                        <td>
                            @if(isset($content))
                                {{ $content }}
                            @endif
                            @yield('content')
                        </td>
                        <td width="20"></td>
                    </tr>
                </table><!-- /BODY -->
            </td>
        </tr>
        <tr>
            <td>
                <!-- FOOTER -->
                <table width="100%" align="center" height="100px" background="{{ URL::to('/images/footer.jpg') }}">
                    <tr>
                        <td style="width: 100%; text-align: center">
                            <p style="font-size:12px; color:white; font-face:Arial;">
                                {{ HTML::image('/images/logo-circle-footer.png', '', ['width' => '30px', 'style' => 'margin-right:7px;']) }}
                                <a style="color:#03A9F4" href="{{ URL::to(Config::get('app.url')) }}">Avtorem.info</a>
                                <span>2010 - 2015</span>
                            </p>
                            @if($userModel)
                                <p style="font-size:11px; color:#cccccc; font-face:Arial;">
                                    Вы получили это письмо, так как являетесь зарегистрированным пользователем сайта
                                    <a style="color:#03A9F4"href="{{ URL::to(Config::get('app.url')) }}">avtorem.info</a>.
                                    Настроить рассылку писем вы можете в разделе
                                    <a style="color:#03A9F4" href="{{ URL::route('user.settings', ['login' => $userModel['alias']]) }}">"Настройки"</a>
                                    своего профиля.
                                </p>
                            @endif
                            @if($getRegistered)
                                <p style="font-size:11px; color:#cccccc; font-face:Arial;">
                                    Зарегистрироваться на сайте
                                    <a style="color:#03A9F4"href="{{ URL::to(Config::get('app.url')) }}">avtorem.info</a>
                                    Вы можете по ссылке
                                    <a style="color:#03A9F4" href="{{ URL::route('register') }}">"Регистрация"</a>.
                                </p>
                            @endif
                        </td>
                    </tr>
                </table><!-- /FOOTER -->
            </td>
        </tr>
    </tbody>
</table>