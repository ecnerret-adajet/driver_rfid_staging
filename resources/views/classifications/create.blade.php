@extends('layouts.app')
@section('content')

    <div class="row">
        <div class="card-panel  light-blue darken-1 white-text z-depth-0 no-edge overlap">
            <div class="had-container">
            </div>
        </div>

        {!! Form::model($classification = new \App\Classification, ['url' => 'classifications', 'files' => 'true', 'enctype' => 'multipart\form-data']) !!}
        {!! csrf_field() !!}
        @include('classifications.form')
        {!! Form::close() !!}
    </div><!-- end row -->


@endsection