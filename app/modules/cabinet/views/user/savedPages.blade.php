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
            @include('cabinet::user.userInfo')

            {{ $areaWidget->leftSidebar() }}

        </div>
        <div class="col-lg-9">
            <div class="row">
                <div class="col-lg-12">
                    <h2>{{ $title }}</h2>

                    <div id="saved-pages">
                        @if(count($pages))
                            @foreach($pages as $page)
                                @if($page->page)
                                    <div data-page-id="{{ $page->page->id }}" class="well">
                                        <div class="row">
                                            @include('cabinet::user.pageInfo', ['page' => $page->page, 'item' => $page])
                                        </div>
                                    </div>
                                @else
                                    <div data-page-id="{{ $page->page_id }}" class="well">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h3>
                                                    <div class="pull-right">
                                                        <a href="javascript:void(0)" id="remove-page" data-id="{{ $page->page_id }}">
                                                            <i class="glyphicon glyphicon-floppy-remove"></i>
                                                        </a>
                                                    </div>
                                                </h3>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="date date-saved">
                                                    <i>
                                                        Сохранено {{ DateHelper::dateFormat($page->created_at) }}
                                                    </i>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <p>
                                                    Статья, которую вы сохранили, была удалена.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            <div>
                                {{ $pages->links() }}
                            </div>
                        @else
                            @if(Auth::user()->is($user))
                                <p>
                                    Вы еще ничего не сохранили.
                                </p>
                            @else
                                <p>
                                    Сохраненных страниц нет.
                                </p>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="col-lg-12">
                    {{ $areaWidget->contentBottom() }}
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    @parent

    <script type="text/javascript">
        $(".remove-page").on('click', function() {
            var $link = $(this);
            var pageId = $link.data('id');
            $.ajax({
                url: "{{ URL::route('user.removePage', ['login' => Auth::user()->getLoginForUrl()]) }}",
                dataType: "text json",
                type: "POST",
                data: {pageId: pageId},
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                },
                success: function(response) {
                    if(response.success){
                        $('[data-page-id=' + pageId + ']').remove();
                    }
                }
            });
        });
    </script>
@endsection
