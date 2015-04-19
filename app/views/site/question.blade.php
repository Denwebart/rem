@extends('layouts.main')

@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ URL::to('/') }}">Главная</a></li>
        @if($page->parent)
            <li>
                <a href="{{ URL::to($page->parent->parent->getUrl()) }}">
                    {{ $page->parent->parent->getTitle() }}
                </a>
            </li>
            <li>
                <a href="{{ URL::to($page->parent->getUrl()) }}">
                    {{ $page->parent->getTitle() }}
                </a>
            </li>
        @endif
        <li>{{ $page->getTitle() }}</li>
    </ol>

    <section id="content" class="well">

        @if($page->title)
            <h2>{{ $page->title }}</h2>
        @endif

        @if(Auth::check())
            <a href="javascript:void(0)" id="save-page" data-page-id="{{ $page->id }}">
                <i class="glyphicon glyphicon-floppy-save"></i>
            </a>

            @section('script')
                <script type="text/javascript">
                    $("#save-page").on('click', function() {
                        var pageId = $(this).data('pageId');
                        $.ajax({
                            url: "{{ URL::route('user.savePage', ['login' => Auth::user()->getLoginForUrl()]) }}",
                            dataType: "text json",
                            type: "POST",
                            data: {pageId: 'pageId'},
                            success: function(response) {
                                alert('done');
//                                if(response.success){
//                                    $('[data-vote-comment-id='+ commentId +']').find('.vote-result').text(response.votesLike - response.votesDislike);
//                                    $('[data-vote-comment-id='+ commentId +']').find('.vote-message').text(response.message);
//                                } else {
//                                    $('[data-vote-comment-id='+ commentId +']').find('.vote-message').text(response.message);
//                                }
                            }
                        });

                    });
                </script>
            @endsection
        @endif

        @if($page->content)
            <div class="content">
                {{ $page->content }}
            </div>
        @endif

        @if(Auth::check())
            <div class="row">
                <div class="col-md-12">
                    <a href="" class="btn btn-success pull-right">Подписаться на вопрос</a>
                </div>
            </div>
        @endif

        <div id="answers">
            {{-- Комментарии --}}
            <?php
                $commentWidget = app('CommentWidget');
                $commentWidget->title = 'Ответы';
                $commentWidget->formTitle = 'Написать ответ';
                $commentWidget->successMessage = 'Спасибо за ответ!';
            ?>
            {{ $commentWidget->show($page) }}
        </div>

    </section>
@stop
