<?php
$title = $parentPage
    ? 'Вопросы категории "' . $parentPage->getTitle() . '"'
    : 'Вопросы';
View::share('title', $title);
?>

<i class="fa fa-question"></i>
{{ $title }}
@if(!$parentPage)
    <small class="hidden-md hidden-sm">вопросы пользователей</small>
@endif