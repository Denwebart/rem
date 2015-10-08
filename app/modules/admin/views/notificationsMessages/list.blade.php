@foreach($notificationsMessages as $notificationMessage)
    <tr>
        <td>{{ $notificationMessage->id }}</td>
        <td>{{ $notificationMessage->description }}</td>
        {{--                                    <td>{{ $notificationMessage->variables }}</td>--}}
        <td>{{ $notificationMessage->message }}</td>
        <td class="button-column one-button">
            <a class="btn btn-info btn-sm" href="{{ URL::route('admin.notificationsMessages.edit', ['id' => $notificationMessage->id, 'backUrl' => isset($url) ? urlencode($url) : urlencode(Request::fullUrl())]) }}">
                <i class="fa fa-edit "></i>
            </a>
        </td>
    </tr>
@endforeach