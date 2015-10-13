@foreach($menus as $menu)
<tr onclick="window.location.href='{{ URL::route('admin.menus.items', ['type' => $menu->type]) }}'; return false">
    <td>{{ Menu::$types[$menu->type] }}</td>
    <td>{{ $menu->pagesCount }}</td>
</tr>
@endforeach