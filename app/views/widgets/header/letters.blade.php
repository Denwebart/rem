<li class="dropdown dropdown-letters">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-envelope"></i>
        @if(count($letters))
            <span class="label label-success">
                {{ count($letters) }}
            </span>
        @endif
    </a>
    <ul class="dropdown-menu">
        <li class="header"><i class="fa fa-envelope"></i> Новые письма: <span>{{ count($letters) }}</span></li>
        <li>
            <ul>
                @foreach($letters as $letter)
                <li>
                    <a href="{{ URL::route('admin.letters.show', ['id' => $letter->id]) }}">
                        <div class="pull-left">
                            {{ HTML::image(Config::get('settings.mini_defaultAvatar'), $letter->name, ['class' => 'img-responsive avatar-default img-rounded']) }}
                        </div>
                        <h4>{{ $letter->name }}
                            <small>
                                <i class="fa fa-clock-o"></i>
                                {{ DateHelper::getRelativeTime($letter->created_at) }}
                            </small>
                        </h4>
                        <p>{{ $letter->email }}</p>
                        @if($letter->subject)
                            <p>{{ $letter->subject }}</p>
                        @endif
                    </a>
                </li>
                @endforeach
            </ul>
        </li>
        <li class="footer"><a href="{{ URL::route('admin.letters.index') }}">Показать все письма</a></li>
    </ul>
</li>