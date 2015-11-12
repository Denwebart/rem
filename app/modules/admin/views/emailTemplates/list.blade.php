@foreach($emailTemplates as $emailTemplate)
    <tr>
        <td>{{ $emailTemplate->id }}</td>
        <td>{{ $emailTemplate->key }}</td>
        <td>
            {{ $emailTemplate->description }}
            <hr>
            Доступные переменные:
            {{ $emailTemplate->variables }}
        </td>
        <td>{{ $emailTemplate->subject }}</td>
        <td>
            @include('layouts.email', ['content' => $emailTemplate->html, 'userIsRegistered' => true])
        </td>
        <td class="button-column one-button">
            <a class="btn btn-info btn-sm" href="{{ URL::route('admin.emailTemplates.edit', ['id' => $emailTemplate->id, 'backUrl' => isset($url) ? urlencode($url) : urlencode(Request::fullUrl())]) }}">
                <i class="fa fa-edit "></i>
            </a>
        </td>
    </tr>
@endforeach