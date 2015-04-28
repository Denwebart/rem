@extends('cabinet::layouts.cabinet')

<?php
$title = (Auth::user()->is($user)) ? 'Сохранённое' : 'Сохранённое пользователем ' . $user->login;
View::share('title', $title);
?>

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('/') }}">Главная</a></li>
                <li>
                    <a href="{{ URL::route('user.profile', ['login' => $user->getLoginForUrl()]) }}">
                        {{ (Auth::user()->is($user)) ? 'Мой профиль' : 'Профиль пользователя ' . $user->login }}
                    </a>
                </li>
                <li>{{ $title }}</li>
            </ol>
        </div>

        <div class="col-lg-3">
            <div class="avatar">
                {{ $user->getAvatar() }}
            </div>
        </div>
        <div class="col-lg-9">
            <h2>{{ $title }}</h2>

            <div id="saved-pages">
                @foreach($pages as $page)
                    @if($page->page)
                        <div data-page-id="{{ $page->page->id }}" class="col-md-12">
                            <div class="well">
                                <div class="pull-right">
                                    <a href="javascript:void(0)" class="remove-page" data-id="{{ $page->page->id }}">
                                        <i class="glyphicon glyphicon-floppy-remove"></i>
                                    </a>
                                </div>
                                <h3>
                                    <a href="{{ URL::to($page->page->getUrl()) }}">
                                        {{ $page->page->getTitle() }}
                                    </a>
                                </h3>
                                <div class="date date-create">
                                    <i>
                                        Добавлена {{ DateHelper::dateFormat($page->created_at) }}
                                    </i>
                                </div>
                                <div>
                                    {{ $page->page->getIntrotext() }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div data-page-id="{{ $page->page_id }}" class="col-md-12">
                            <div class="well">
                                <div class="pull-right">
                                    <a href="javascript:void(0)" id="remove-page" data-id="{{ $page->page_id }}">
                                        <i class="glyphicon glyphicon-floppy-remove"></i>
                                    </a>
                                </div>
                                <div class="date date-create">
                                    <i>
                                        Добавлена {{ DateHelper::dateFormat($page->created_at) }}
                                    </i>
                                </div>
                                <div>
                                    Страница была удалена
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

                <div>
                    {{ $pages->links() }}
                </div>

            </div>

        </div>
    </div>
@stop

@section('script')
    <script type="text/javascript">
        $(".remove-page").on('click', function() {
            var $link = $(this);
            var pageId = $link.data('id');
            $.ajax({
                url: "{{ URL::route('user.removePage', ['login' => Auth::user()->getLoginForUrl()]) }}",
                dataType: "text json",
                type: "POST",
                data: {pageId: pageId},
                success: function(response) {
                    if(response.success){
                        $('[data-page-id=' + pageId + ']').remove();
                    }
                }
            });
        });
    </script>
@endsection
