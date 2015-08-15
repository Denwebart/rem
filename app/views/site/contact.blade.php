@extends('layouts.main')

@section('content')
	<ol class="breadcrumb">
		<li><a href="{{ URL::to('/') }}">Главная</a></li>
		<li>{{ $page->getTitleForBreadcrumbs() }}</li>
	</ol>
	
	<section id="content" class="well">

		@if($page->title)
			<h2>{{ $page->title }}</h2>
		@endif

		{{ $areaWidget->contentTop() }}

		@if($page->content)
			<div class="content">
                @if($page->image)
                    <a class="fancybox" rel="group-content" href="{{ $page->getImageLink('origin') }}">
                        {{ $page->getImage() }}
                    </a>
                @endif
				{{ $page->getContentWithWidget() }}
			</div>
		@endif

		{{ $areaWidget->contentMiddle() }}

		<section id="contact-form-area">

            <!-- всплывающее сообщение - отправка контактной формы -->
			@if(Session::has('successMessage'))
                @section('siteMessages')
                    @include('widgets.siteMessages.info', ['siteMessage' => Session::get('successMessage')])
                    @parent
                @endsection
			@endif

			{{ Form::open([
				  'action' => ['SiteController@contactPost'],
				  'id' => 'contact-form',
				  'class' => 'form-horizontal'
				  ])
			}}
                <fieldset>

                    <!-- Form Name -->
                    <legend>Обратная связь</legend>

                    @if(Auth::check())
                        <a href="{{ URL::route('user.profile', ['login' => Auth::user()->getLoginForUrl()]) }}" class="avatar-link pull-left">
                            {{ Auth::user()->getAvatar('mini', ['class' => 'media-object avatar circle']) }}
                            <span class="is-online-status online" title="Сейчас на сайте" data-toggle="tooltip" data-placement="top"></span>
                        </a>
                        <a href="{{ URL::route('user.profile', ['login' => Auth::user()->getLoginForUrl()]) }}" class="pull-left">
                            <span>{{  Auth::user()->login }}</span>
                        </a>

                        <div class="clearfix"></div>
                    @else
                        <!-- Имя -->
                        <div class="form-group">
                            {{ HTML::decode(Form::label('user_name', 'Введите Ваше имя: <span class="text-danger">*</span>', ['class' => 'col-md-4 control-label'])) }}
                            <div class="col-md-8">
                                {{ Form::text('user_name', '', ['class' => 'form-control input-md', 'placeholder' => 'Имя*']); }}
                                @if ($errors->has('user_name')) <p class="text-danger">{{ $errors->first('user_name') }}</p> @endif
                            </div>
                        </div>
                        <!-- Email -->
                        <div class="form-group">
                            {{ HTML::decode(Form::label('user_email', 'Адрес e-mail: <span class="text-danger">*</span>', ['class' => 'col-md-4 control-label'])) }}
                            <div class="col-md-8">
                                {{ Form::text('user_email', '', ['class' => 'form-control input-md', 'placeholder' => 'Email*']); }}
                                @if ($errors->has('user_email')) <p class="text-danger">{{ $errors->first('user_email') }}</p> @endif
                            </div>
                        </div>
                    @endif

                    <!-- Text input-->
                    <div class="form-group">
                        {{ Form::label('subject', 'Тема сообщения:', ['class' => 'col-md-4 control-label']) }}
                        <div class="col-md-8">
                            {{ Form::text('subject', '', ['class' => 'form-control input-md', 'placeholder' => 'Тема сообщения']); }}
                            @if ($errors->has('subject')) <p class="text-danger">{{ $errors->first('subject') }}</p> @endif
                        </div>
                    </div>

                    <!-- Textarea -->
                    <div class="form-group">
                        {{ HTML::decode(Form::label('message', 'Введите текст сообщения: <span class="text-danger">*</span>', ['class' => 'col-md-4 control-label'])) }}
                        <div class="col-md-8">
                            {{ Form::textarea('message', '', ['class' => 'form-control', 'placeholder' => 'Сообщение*']); }}
                            @if ($errors->has('message')) <p class="text-danger">{{ $errors->first('message') }}</p> @endif
                        </div>
                    </div>

                    <!-- Multiple Checkboxes (inline) -->
                    <div class="form-group">
                        {{ Form::label('subject', 'Отправить копию этого сообщения на Ваш адрес e-mail', ['class' => 'col-md-4 control-label']) }}
                        <div class="col-md-8">
                            <div class="checkbox">
                                <label>
                                    {{ Form::checkbox('sendCopy', 0, ['class' => 'form-control']); }}
                                </label>
                            </div>
                        </div>
                    </div>

                    @if(!Auth::check())
                        {{ Form::captcha() }}
                        @if ($errors->has('g-recaptcha-response'))
                            <p class="text-danger">{{ $errors->first('g-recaptcha-response') }}</p>
                        @endif
                    @endif

                    {{ Form::submit('Отправить', ['id'=> 'submit', 'class' => 'btn btn-prime btn-mid pull-right']) }}

                </fieldset>
                {{ Form::hidden('_token', csrf_token()) }}
			{{ Form::close() }}
		</section><!--contact-form-area-->

		{{ $areaWidget->contentBottom() }}
	</section>
@stop