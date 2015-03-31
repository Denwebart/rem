@extends('layouts.users')

<?php
$title = 'Все пользователи';
View::share('page', $title);
?>

@section('content')
    <section id="content">
        <h2>{{ $title }}</h2>

        {{ Form::open(['method' => 'GET', 'route' => ['users'], 'files' => true], ['id' => 'search-users-form']) }}

        <div class="col-md-10">
            <div class="form-group">
                {{ Form::text('name', $name, ['class' => 'form-control', 'id' => 'name']) }}
                {{ Form::hidden('rsponse', $name, ['class' => 'form-control', 'id' => 'rsponse']) }}
            </div>
        </div>
        <div class="col-md-2">
            {{ Form::submit('Найти', ['class' => 'btn btn-success']) }}
        </div>

        {{ Form::close() }}

        <div id="users">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-4"></div>
                <div class="col-md-2">Статьи</div>
                <div class="col-md-2">Вопросы</div>
                <div class="col-md-2">Комменатрии</div>
            </div>

            @foreach($users as $user)

                <div class="row margin-bottom-20">
                    <div class="col-md-2">
                        <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                            {{ $user->getAvatar('mini') }}
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                            {{ $user->login }}
                        </a>
                        @if($user->getFullName())
                            <p>{{ $user->getFullName() }}</p>
                        @endif

                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-2"></div>
                    <div class="col-md-2">
                        <a href="{{ URL::route('user.comments', ['login' => $user->getLoginForUrl()]) }}">
                            {{ count($user->publishedСomments) }}
                        </a>
                    </div>
                </div>

            @endforeach

            {{ $users->links() }}

        </div>
    </section>
@stop

@section('style')
    <link rel="stylesheet" href="/css/jquery-ui.min.css"/>
@stop

@section('script')
    @parent

    <script src="/js/jquery-ui.min.js"></script>

    <script type="text/javascript">

        $("#name").autocomplete({
            source: "<?php echo URL::route('users.autocomplete') ?>",
            minLength: 1,
            select: function(e, ui) {
//                console.log('selectd');
            }
        });

    </script>

    {{--<script type="text/javascript">--}}

        {{--$("form[id^='search-users-form']").submit(function(event) {--}}
            {{--event.preventDefault ? event.preventDefault() : event.returnValue = false;--}}
            {{--var $form = $(this),--}}
                    {{--data = $form.serialize(),--}}
                    {{--url = $form.attr('action');--}}
            {{--var posting = $.post(url, { formData: data });--}}
            {{--posting.done(function(data) {--}}
                {{--if(data.success) {--}}
                    {{--var successContent = '<div class="message"><h3>Ваш комментарий успешно отправлен!</h3></div>';--}}
                    {{--$('#successMessage').html(successContent);--}}
                    {{--$($form).trigger('reset');--}}
                {{--} //success--}}
            {{--}); //done--}}
        {{--});--}}

    {{--</script>--}}
@stop