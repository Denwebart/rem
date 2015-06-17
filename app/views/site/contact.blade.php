@extends('layouts.main')

@section('content')
	<ol class="breadcrumb">
		<li><a href="{{ URL::to('/') }}">Главная</a></li>
		<li>{{ $page->getTitle() }}</li>
	</ol>
	
	<section id="content" class="well">

		@if($page->title)
			<h2>{{ $page->title }}</h2>
		@endif
		@if($page->content)
			<div class="content">
				{{ $page->content }}
			</div>
		@endif

		<section id="contact-form-area">
			@if(Session::has('successMessage'))
				{{ Session::get('successMessage') }}
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

				<!-- Text input-->
				<div class="form-group">
					{{ HTML::decode(Form::label('name', 'Введите Ваше имя: <span class="text-danger">*</span>', ['class' => 'col-md-4 control-label'])) }}
					<div class="col-md-8">
						{{ Form::text('name', '', ['class' => 'form-control input-md', 'placeholder' => 'Имя*']); }}
						@if ($errors->has('name')) <p class="text-danger">{{ $errors->first('name') }}</p> @endif
					</div>
				</div>

				<!-- Text input-->
				<div class="form-group">
					{{ HTML::decode(Form::label('email', 'Адрес e-mail: <span class="text-danger">*</span>', ['class' => 'col-md-4 control-label'])) }}
					<div class="col-md-8">
						{{ Form::text('email', '', ['class' => 'form-control input-md', 'placeholder' => 'Email*']); }}
						@if ($errors->has('email')) <p class="text-danger">{{ $errors->first('email') }}</p> @endif
					</div>
				</div>

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

				{{ Form::captcha() }}
				@if ($errors->has('g-recaptcha-response')) <p class="text-danger">{{ $errors->first('g-recaptcha-response') }}</p> @endif

				{{ Form::submit('Отправить', ['id'=> 'submit', 'class' => 'btn btn-prime btn-mid pull-right']) }}

			</fieldset>
			{{ Form::close() }}
		</section><!--contact-form-area-->
	</section>
@stop