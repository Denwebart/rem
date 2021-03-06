@extends('layouts.main')

@section('breadcrumbs')
    <!-- Breadcrumbs -->
    @include('widgets.breadcrumbs', ['items' => [
        [
            'title' => $page->getTitleForBreadcrumbs()
        ]
    ]])
@stop

@section('content')
	<section id="content" class="well" itemscope itemtype="http://schema.org/Article">

        <meta itemprop="datePublished" content="{{ DateHelper::dateFormatForSchema($page->published_at) }}">

		@if($page->is_show_title)
			<h2 itemprop="headline">{{ $page->title }}</h2>
        @else
            <meta itemprop="headline" content="{{ $page->getTitle() }}">
		@endif

		{{ $areaWidget->contentTop() }}

		@if($page->content)
			<div class="content" itemprop="articleBody">
                @if($page->image)
                    <a class="fancybox pull-left" data-fancybox-group="group-content" href="{{ $page->getImageLink('origin') }}">
                        {{ $page->getImage('origin', ['class' => 'page-image']) }}
                    </a>
                @else
                    <meta itemprop="image" content="{{ URL::to(Config::get('settings.defaultImage')) }}">
                @endif
				{{ $page->getContentWithWidget() }}
			</div>
		@endif

		{{ $areaWidget->contentMiddle() }}

		<section id="contact-form-area">

			{{ Form::open([
				  'action' => ['SiteController@contactPost'],
				  'id' => 'contact-form',
				  ])
			}}
                <fieldset>
                    <h3>Обратная связь</h3>

                    <div class="row">
                        @if(Auth::check())
                            <div class="col-md-12">
                                <a href="{{ URL::route('user.profile', ['login' => Auth::user()->getLoginForUrl()]) }}" class="login pull-left">
                                    <span>{{  Auth::user()->login }}</span>
                                </a>
                            </div>
                        @else
                            <!-- Имя -->
                            <div class="form-group col-md-6">
                                {{ Form::text('user_name', '', ['class' => 'form-control', 'placeholder' => 'Имя*']); }}
                                @if ($errors->has('user_name')) <p class="text-danger">{{ $errors->first('user_name') }}</p> @endif
                            </div>
                            <!-- Email -->
                            <div class="form-group col-md-6">
                                {{ Form::text('user_email', '', ['class' => 'form-control', 'placeholder' => 'Email*']); }}
                                @if ($errors->has('user_email')) <p class="text-danger">{{ $errors->first('user_email') }}</p> @endif
                            </div>
                        @endif

                        <!-- Text input-->
                        <div class="form-group col-md-12">
                            {{ Form::text('subject', '', ['class' => 'form-control', 'placeholder' => 'Тема сообщения']); }}
                            @if ($errors->has('subject')) <p class="text-danger">{{ $errors->first('subject') }}</p> @endif
                        </div>

                        <!-- Textarea -->
                        <div class="form-group col-md-12">
                            {{ Form::textarea('message', '', ['class' => 'form-control', 'placeholder' => 'Сообщение*']); }}
                            @if ($errors->has('message')) <p class="text-danger">{{ $errors->first('message') }}</p> @endif
                        </div>

                        <div class="form-group col-md-12">
                            <div class="checkbox">
                                <label>

                                    {{ Form::checkbox('sendCopy', 1, 1); }}
                                    <span class="margin-left-10">
                                        Отправить копию этого сообщения на Ваш адрес e-mail
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            @if(!Auth::check())
                                {{ Form::captcha() }}
                                @if ($errors->has('g-recaptcha-response'))
                                    <p class="text-danger">{{ $errors->first('g-recaptcha-response') }}</p>
                                @endif
                            @endif
                        </div>

                        <div class="col-md-4 col-md-offset-8 col-sm-4 col-sm-offset-8 col-xs-12 col-xs-offset-0">
                            {{ Form::submit('Отправить', ['id'=> 'submit', 'class' => 'btn btn-success btn-sm btn-full pull-right margin-top-20']) }}
                        </div>
                    </div>
                </fieldset>
                {{ Form::hidden('_token', csrf_token()) }}
			{{ Form::close() }}
		</section><!--contact-form-area-->

		{{ $areaWidget->contentBottom() }}
	</section>
@stop