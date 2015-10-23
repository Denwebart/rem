@extends('layouts.main')

<?php
$areaWidget = App::make('AreaWidget', ['pageType' => AdvertisingPage::PAGE_SITE]);
View::share('areaWidget', $areaWidget);
?>

@section('breadcrumbs')
    <!-- Breadcrumbs -->
    @include('widgets.breadcrumbs', ['items' => [
        [
            'title' => $page->getTitle()
        ]
    ]])
@stop

@section('content')
    <section id="content" class="well">

        <h2>{{ $page->title }}</h2>

        @if($page->content)
            <div class="content">
                @if($page->image)
                    <a class="fancybox" rel="group-content" href="{{ $page->getImageLink('origin') }}">
                        {{ $page->getImage('origin') }}
                    </a>
                @endif
                {{ $page->getContentWithWidget() }}
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