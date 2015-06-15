@extends('cabinet::layouts.cabinet')

<?php
$title = 'Соглашение с правилами сайта';
View::share('title', $title);

$headerWidget = app('HeaderWidget');
View::share('headerWidget', $headerWidget);
?>

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('/') }}">Главная</a></li>
                <li>{{ $title }}</li>
            </ol>
        </div>

        <div class="col-lg-3">
            <div class="avatar">
                {{ $user->getAvatar() }}
            </div>
        </div>
        <div class="col-lg-9 well">
            <h2>{{ $title }}</h2>

            @if(Session::has('message'))
                <div class="alert alert-dismissable alert-danger">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <p>{{ Session::get('message') }}</p>
                </div>
            @endif

            @if(count($rules))
                {{ Form::open(['action' => ['UsersController@postRules'], 'role' => 'form', 'class' => '']) }}
                    {{ Form::hidden('backUrl', $backUrl) }}
                    <div id="rules">
                        @foreach($rules as $key => $rule)
                            <div class="row rule {{ (0 == $key) ? '' : 'opacity'}}" data-rule-id="{{ $key }}">
                                <div class="col-md-1">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" data-checkbox-rule-id="{{ $key }}" class="checkbox-input" name="rules[{{ $key }}]" {{ (0 == $key) ? '' : 'disabled="disabled"'}}>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-11">
                                    <h3>{{ $rule->position }}. {{ $rule->title }}</h3>
                                    {{ $rule->description }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{ Form::submit('Подтвердить', ['id'=> 'submit', 'class' => 'btn btn-success pull-right', 'disabled' => true]) }}

                {{ Form::close() }}
            @endif

        </div>
    </div>
@stop

@section('script')
    @parent

    <script type="text/javascript">
        $('.checkbox-input').attr('checked', false);


        var yellowTimeoutId, greenTimeoutId;
        $('.checkbox-input').change(function(){
            var thisId = $(this).data('checkboxRuleId');
            var $thisRule = $('[data-rule-id="'+ thisId +'"]');
            var nextId = thisId + 1;
            var $nextRule = $('[data-rule-id="'+ nextId +'"]');
            if(this.checked){
                if(!$thisRule.is(':last-child')) {
                    $nextRule.removeClass('opacity');
                    $nextRule.find('.check').css('color', '#F44336').addClass('loader-checkbox');
                    yellowTimeoutId = setTimeout(function() {
                        var $nextRule = $('[data-rule-id="'+ nextId +'"]');
                        $nextRule.find('.check').css('color', '#FFAB00');
                        greenTimeoutId = setTimeout(function() {
                            var $nextRule = $('[data-rule-id="'+ nextId +'"]');
                            $nextRule.find('.check').css('color', '#4CAF50').removeClass('loader-checkbox');
                            $nextRule.find('.checkbox-input').removeAttr('disabled');
                        }, 2500, nextId);
                    }, 2500, nextId);
                }
                else {
                    $('#submit').removeAttr('disabled');
                }
            } else {
                $thisRule.nextAll().addClass('opacity');
                $thisRule.nextAll().find('.checkbox-input').attr('disabled', 'disabled');
                $thisRule.nextAll().find('.checkbox-input').attr('checked', false);
                $thisRule.nextAll().find('.check').css('color', '#333').removeClass('loader-checkbox');
                clearTimeout(yellowTimeoutId);
                clearTimeout(greenTimeoutId);
                $('#submit').attr('disabled', 'disabled');
            }
        });
    </script>
@endsection