@extends('layouts.main')

<?php
$areaWidget = App::make('AreaWidget', ['pageType' => AdvertisingPage::PAGE_SITE]);
View::share('areaWidget', $areaWidget);
?>

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('/') }}">Главная</a></li>
        <li>{{ $page->getTitle() }}</li>
    </ol>

    <section id="content" class="well">

        <h2>{{ $page->title }}</h2>

        @if($page->content)
            <div class="content">
                {{ $page->content }}
            </div>
        @endif

        @if(count($rules))
            <section id="rules-area">
                @foreach($rules as $rule)
                    <div class="row rule">
                        <div class="col-md-12">
                            <h3>{{ $rule->position }}. {{ $rule->title }}</h3>
                            {{ $rule->description }}
                        </div>
                    </div>
                @endforeach
            </section><!--rules-area-->
        @endif

    </section>
@stop