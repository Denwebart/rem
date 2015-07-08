<?php $type = isset($type) ? $type : null ?>
<div class="alert alert-dismissable alert-warning">
    <h4>Вы не согласились с правилами сайта!</h4>
    <p>Пока вы не соглавитесь с правилами сайта,
        вы не сможете @if(Page::TYPE_QUESTION == $type) отвечать на вопросы. @else оставлять комментарии. @endif
        Для ознакомления с правилами перейдите по <a href="{{ URL::route('rules', ['rulesAlias' => 'rules', 'backUrl' => urlencode($backUrl)]) }}" class="alert-link">ссылке</a>.</p>
</div>