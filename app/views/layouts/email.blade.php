<!-- HEADER -->
<table width="100%" bgcolor="#eeeeee">
    <tr>
        <td></td>
        <td>
            <div>
                <table>
                    <tr>
                        <td>
                            <a href="{{ Config::get('app.url') }}">
                                {{ HTML::image('images/logo-300.png') }}
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
        <td></td>
    </tr>
</table><!-- /HEADER -->

<!-- BODY -->
<table bgcolor="#eeeeee">
    <tr>
        <td></td>
        <td>
            <table>
                <tr>
                    <td>
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
        <td>

            <!-- content -->
            <div>
                <table>
                    <tr>
                        <td>
                            <p>
                                <font size="12" color="white" face="Arial">Какой-то текст в футере.</font>
                            </p>
                            {{--<p>--}}
                            {{--<a href="#">Terms</a> |--}}
                            {{--<a href="#">Privacy</a> |--}}
                            {{--<a href="#"><unsubscribe>Unsubscribe</unsubscribe></a>--}}
                            {{--</p>--}}
                        </td>
                    </tr>
                </table>
            </div><!-- /content -->

        </td>
        <td></td>
    </tr>
</table><!-- /FOOTER -->