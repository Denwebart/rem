@extends('layouts.main')

@section('content')
	<ol class="breadcrumb">
        <li class="home-page">
            <a href="{{ URL::to('/') }}">
                <i class="material-icons">home</i>
            </a>
        </li>
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
                        {{ $page->getImage('origin') }}
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
                            {{ Form::label('sendCopy', 'Отправить копию этого сообщения на Ваш адрес e-mail', ['class' => 'control-label send-copy']) }}
                            <div class="checkbox">
                                <label>
                                    {{ Form::checkbox('sendCopy', 1, 1, ['class' => 'form-control']); }}
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
                    </div>

                    {{ Form::submit('Отправить', ['id'=> 'submit', 'class' => 'btn btn-success btn-sm pull-right']) }}

                </fieldset>
                {{ Form::hidden('_token', csrf_token()) }}
			{{ Form::close() }}
		</section><!--contact-form-area-->

		{{ $areaWidget->contentBottom() }}
	</section>
@stop