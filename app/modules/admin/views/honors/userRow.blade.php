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
        @foreach($user->userHonors as $userHonor)
            <a href="{{ URL::route('admin.honors.show', ['id' => $honor->id]) }}">
                {{ $userHonor->honor->getImage(null, [
                    'width' => '25px',
                    'title' => !is_null($userHonor->comment)
                        ? $userHonor->honor->title . ' ('. $userHonor->comment .')'
                        : $userHonor->honor->title,
                    'alt' => $userHonor->honor->title])
                }}
            </a>
        @endforeach
    </td>
</tr>