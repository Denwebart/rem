<li class="dropdown dropdown-messages">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-envelope"></i><span class="label label-success">{{ count($letters) }}</span>
    </a>
    <ul class="dropdown-menu">
        <li class="header"><i class="fa fa-envelope"></i> Новые письма: {{ count($letters) }}</li>
        <li>
            <ul>
                @foreach($letters as $letter)
                <li>
                    <a href="{{ URL::route('admin.letters.show', ['id' => $letter->id]) }}">
                        <div class="pull-left">
                            {{ HTML::image(Config::get('settings.defaultAvatar'), $letter->name, ['class' => 'img-rounded']) }}
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