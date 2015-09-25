<?php
$title = $parentPage
    ? 'Подпункты страницы "' . $parentPage->getTitle() . '"'
    : 'Страницы';
View::share('title', $title);
?>

<i class="fa fa-file"></i>
{{ $title }}
@if(!$parentPage)
    <small>все страницы сайта</small>
@endif