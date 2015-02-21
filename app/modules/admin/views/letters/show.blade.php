@extends('admin::layouts.admin')

@section('content')
    <div class="page-head">
        <h1>Просмотр письма <small>содержимое письма</small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL::to('admin') }}">Главная</a></li>
            <li class="active"><a href="{{ URL::route('admin.pages.index') }}">Письма</a></li>
            <li>Отправитель: {{ $letter->name }} ({{ $letter->email }})</li>
        </ol>
    </div>

    <div class="content">
        <!-- Main row -->
        <div class="row">

            <div class="col-md-12">
                <h4 class="no-margin-top">Отправитель: {{ $letter->name }} ({{ $letter->email }})</h4>
            </div>

            <div class="col-md-6">
                <div class="box">
                    <div class="box-title">
                        <h3>{{ $letter->subject }}</h3>
                    </div>
                    <div class="box-body">
                        <p>{{ $letter->message }}</p>
                    </div>
                </div>
            </div><!-- ./col -->
            <div class="col-md-6">
                {{--<div class="box">--}}
                    {{--<div class="box-title">--}}
                        {{--<h3>Addresses</h3>--}}
                    {{--</div>--}}
                    {{--<div class="box-body clearfix">--}}
                        {{--<address>--}}
                            {{--<strong>Twitter, Inc.</strong><br>--}}
                            {{--795 Folsom Ave, Suite 600<br>--}}
                            {{--San Francisco, CA 94107<br>--}}
                            {{--<abbr title="Phone">P:</abbr> (123) 456-7890--}}
                        {{--</address>--}}

                        {{--<address>--}}
                            {{--<strong>Full Name</strong><br>--}}
                            {{--<a href="mailto:#">first.last@example.com</a>--}}
                        {{--</address>--}}
                    {{--</div>--}}
                {{--</div><!-- /.box -->--}}
            </div><!-- ./col -->
        </div><!-- /.row -->
    </div>

@stop