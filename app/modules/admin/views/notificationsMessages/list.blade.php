@foreach($notificationsMessages as $notificationMessage)
    <tr>
        <td>{{ $notificationMessage->id }}</td>
        <td>{{ $notificationMessage->description }}</td>
        {{--                                    <td>{{ $notificationMessage->variables }}</td>--}}
        <td>{{ $notificationMessage->message }}</td>
        <td>
            <a class="btn btn-info btn-sm" href="{{ URL::route('admin.notificationsMessages.edit', $notificationMessage->id) }}">
                <i class="fa fa-edit "></i>
            </a>
        </td>
    </tr>
@endforeach