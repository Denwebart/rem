<div class="alert alert-dismissable alert-danger">
    <h4>Вы были забанены администратором сайта.</h4>
    <p>
        Причина: "{{ Auth::user()->latestBanNotification->message }}".
        <br/>
        Теперь для вас недоступны такие разделы личного кабинета,
        как "Редактирование профиля", "Мой автомобиль",
        "Мои вопросы", "Мой журнал", "Мои комментарии", "Личные сообщения".
    </p>
</div>