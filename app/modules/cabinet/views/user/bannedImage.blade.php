{{ HTML::image(Config::get('settings.bannedImage'),
    'Забанен ' . DateHelper::dateFormat($user->latestBanNotification->ban_at) . '. Причина бана: "' . $user->latestBanNotification->message . '"',
    [
        'class' => 'img-responsive banned-image',
        'title' => 'Забанен ' . DateHelper::dateFormat($user->latestBanNotification->ban_at) . '. Причина бана: "' . $user->latestBanNotification->message . '"'
    ])
}}