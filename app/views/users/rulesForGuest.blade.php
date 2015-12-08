@extends('layouts.main')

<?php
$areaWidget = App::make('AreaWidget', ['pageType' => AdvertisingPage::PAGE_SYSTEM]);
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
    <section id="content" class="well" itemscope itemtype="http://schema.org/Article">

        <meta itemprop="datePublished" content="{{ DateHelper::dateFormatForSchema($page->published_at) }}">

        <h2 itemprop="headline">{{ $page->title }}</h2>

        <div class="content" itemprop="articleBody">
            @if($page->image)
                <a class="fancybox pull-left" data-fancybox-group="group-content" href="{{ $page->getImageLink('origin') }}">
                    {{ $page->getImage('origin', ['class' => 'page-image']) }}
                </a>
            @else
                <meta itemprop="image" content="{{ URL::to(Config::get('settings.defaultImage')) }}">
            @endif
            {{ $page->getContentWithWidget() }}

            @if(count($rules))
                <div class="clearfix"></div>
                <div id="rules-area" class="margin-top-20">
                    @foreach($rules as $rule)
                        <div class="row rule">
                            <div class="col-md-12">
                                <h3>{{ $rule->position }}. {{ $rule->title }}</h3>
                                {{ $rule->description }}
                            </div>
                        </div>
                    @endforeach
                </div><!--rules-area-->
            @endif
        </div>
    </section>
@stop