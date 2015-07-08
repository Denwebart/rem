<div class="alert alert-dismissable alert-warning">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <h4>Вы не согласились с правилами сайта!</h4>
    <p>Пока вы не соглавитесь с правилами сайта, для вас будут
        недоступны такие разделы личного кабинета, как "Редактирование профиля", "Мой автомобиль",
        "Мои вопросы", "Мой журнал", "Мои комментарии", "Личные сообщения".
        Для ознакомления с правилами перейдите по <a href="{{ URL::route('rules', ['rulesAlias' => 'rules', 'backUrl' => urlencode(Request::url())]) }}" class="alert-link">ссылке</a>.</p>
</div>