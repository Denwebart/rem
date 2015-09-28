@foreach($settings as $setting)
    <tr>
        <td>{{ $setting->id }}</td>
        <td>{{ $setting->key }}</td>
        <td>{{ $setting->category }}</td>
        <td>{{ Setting::$types[$setting->type] }}</td>
        <td>{{ $setting->title }}</td>
        <td>{{ $setting->description }}</td>
        <td>
            @if($setting->key != 'categoriesOnMainPage')
                {{ $setting->value }}
            @else
                <ul>
                    @foreach(Page::whereIn('id', explode(',', $setting->value))->whereParentId(0)->get() as $item)
                        <li>
                            {{ $item->getTitle() }}
                            @foreach(Page::whereIn('id', explode(',', $setting->value))->where('parent_id', '!=', 0)->get() as $subitem)
                                <ul>
                                    @if($item->id == $subitem->parent_id)
                                        <li>
                                            {{ $subitem->getTitle() }}
                                        </li>
                                    @endif
                                </ul>
                            @endforeach
                        </li>
                    @endforeach
                </ul>
            @endif
        </td>
        <td>
            @if($setting->is_active)
                <span class="label label-success">Активна</span>
            @else
                <span class="label label-warning">Неактивна</span>
            @endif
        </td>
        <td>
            <a class="btn btn-info btn-sm" href="{{ URL::route('admin.settings.edit', $setting->id) }}">
                <i class="fa fa-edit "></i>
            </a>
        </td>
    </tr>
@endforeach