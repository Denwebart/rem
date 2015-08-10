<li class="dropdown dropdown-letters">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="material-icons">local_post_office</i>
        @if(count($letters))
            <span class="label label-success">
                {{ $letters->getTotal() }}
            </span>
        @endif
    </a>
    <ul class="dropdown-menu">
        <li class="header">
            <i class="material-icons">local_post_office</i>
            Новые письма:
            @if($letters->count() < $letters->getTotal())
                <span>{{ $letters->count() }} из {{ $letters->getTotal() }}</span>
            @else
                <span>{{ $letters->count() }}</span>
            @endif
        </li>
        <li>
            <ul>
                @foreach($letters as $letter)
                <li>
                    <a href="{{ URL::route('admin.letters.show', ['id' => $letter->id]) }}">
                        <div class="pull-left">
                            @if($letter->user)
                                {{ $letter->user->getAvatar('mini', ['class' => 'img-rounded']) }}
                            @else
                                {{ HTML::image(Config::get('settings.mini_defaultAvatar'), $letter->user_name, ['class' => 'img-responsive avatar-default img-rounded']) }}
                            @endif
                        </div>
                        <h4>
                            @if($letter->user)
                                {{ $letter->user->login }}
                            @else
                                {{ $letter->user_name }}
                            @endif
                            <small>
                                <i class="material-icons">access_time</i>
                                {{ DateHelper::getRelativeTime($letter->created_at) }}
                            </small>
                        </h4>
                        <p>
                            @if($letter->user)
                                {{ $letter->user->email }}
                            @else
                                {{ $letter->user_email }}
                            @endif
                        </p>
                        @if($letter->subject)
                            <p>{{ $letter->subject }}</p>
                        @endif
                    </a>
                </li>
                @endforeach
            </ul>
        </li>
        <li class="footer">
            <a href="{{ URL::route('admin.letters.index') }}">
                Показать все
            </a>
        </li>
    </ul>
</li>