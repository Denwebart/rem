<tr>
    <td>{{ $user->id }}</td>
    <td>
        <a href="{{ URL::route('user.profile', ['login' => $user->login]) }}">
            {{ $user->getAvatar('mini') }}
        </a>
    </td>
    <td>{{ $user->login }}</td>
    <td>{{ $user->getFullName() }}</td>
    <td></td>
    <td>
        @foreach($user->honors as $userHonor)
            <a href="{{ URL::route('admin.honors.show', ['id' => $userHonor->id]) }}">
                {{ $userHonor->getImage(null, ['width' => '25px']) }}
            </a>
        @endforeach
    </td>
</tr>