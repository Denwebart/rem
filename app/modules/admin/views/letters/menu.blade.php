<ul class="nav nav-pills nav-stacked">
    <li class="{{ Request::is('admin/letters') ? 'active' : ''}}">
        <a href="{{ URL::route('admin.letters.index') }}">
            <i class="fa fa-inbox"></i>
            Входящие письма
            @if($newLetters = count($headerWidget->newLetters()))
                <span class="label label-info pull-right">
                    {{ $newLetters }}
                </span>
            @endif
        </a>
    </li>
    <li class="{{ Request::is('admin/letters/sent') ? 'active' : ''}}">
        <a href="{{ URL::route('admin.letters.sent') }}">
            <i class="fa fa-envelope"></i>
            Отправленные письма
        </a>
    </li>
    <li class="{{ Request::is('admin/letters/trash') ? 'active' : ''}}">
        <a href="{{ URL::route('admin.letters.trash') }}">
            <i class="fa fa-trash-o"></i>
            Удаленные письма
            @if(count($headerWidget->deletedLetters))
                <span class="label label-danger pull-right">
                    {{ count($headerWidget->deletedLetters) }}
                </span>
            @endif
        </a>
    </li>
    {{--<li><a href="#"><i class="fa fa-star"></i> Важные письма</a></li>--}}
</ul>