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
                    <a href="#">
                        <div class="pull-left">
                            {{ HTML::image(Config::get('settings.defaultAvatar'), $letter->name, ['class' => 'img-rounded']) }}
                        </div>
                        <h4>{{ $letter->name }}<small><i class="fa fa-clock-o"></i> {{ DateHelper::dateFormat($letter->created_at) }}</small></h4>
                        <p>{{ $letter->email }}</p>
                        @if($letter->subject)
                            <p>{{ $letter->subject }}</p>
                        @endif
                    </a>
                </li>
                @endforeach
            </ul>
        </li>
        {{--URL::route('admin.letters.index')--}}
        <li class="footer"><a href="">Показать все письма</a></li>
    </ul>
</li>