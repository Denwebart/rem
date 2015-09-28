<?php
$title = $parentPage
    ? 'Вопросы категории "' . $parentPage->getTitle() . '"'
    : 'Вопросы';
View::share('title', $title);
?>

<i class="fa fa-question"></i>
{{ $title }}
@if(!$parentPage)
    <small>вопросы пользователей</small>
@endif