@foreach($settings as $setting)
    <tr>
        <td>{{ $setting->id }}</td>
        {{--<td>{{ $setting->key }}</td>--}}
        <td>{{ $setting->category }}</td>
        {{--<td>{{ Setting::$types[$setting->type] }}</td>--}}
        <td>{{ $setting->title }}</td>
        <td>{{ $setting->description }}</td>
        <td>
            @if($setting->key == 'categoriesOnMainPage')
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
            @elseif($setting->key == 'theme')
                {{ Setting::$themeValues[$setting->value] }}
            @else
                @if($setting->type == Setting::TYPE_BOOLEAN)
                    @if($setting->value)
                        <span class="label label-success">Включена</span>
                    @else
                        <span class="label label-warning">Отключена</span>
                    @endif
                @else
                    {{{ $setting->value }}}
                @endif
            @endif
        </td>
        <td class="status">
            @if($setting->is_active)
                <span class="published" title="Активна" data-toggle="tooltip"></span>
            @else
                <span class="not-published" title="Неактивна" data-toggle="tooltip"></span>
            @endif
        </td>
        <td class="button-column one-button">
            <a class="btn btn-info btn-sm" href="{{ URL::route('admin.settings.edit', ['id' => $setting->id, 'backUrl' => isset($url) ? urlencode($url) : urlencode(Request::fullUrl())]) }}">
                <i class="fa fa-edit "></i>
            </a>
        </td>
    </tr>
@endforeach