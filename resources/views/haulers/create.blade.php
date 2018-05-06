@extends('layouts.app')
@section('content')

            <div class="row">

                <div class="col s12">
                    <h4 class="form-title">Add New Hauler</h4>
                </div>

                {!! Form::model($hauler = new \App\Hauler, ['url' => 'haulers', 'files' => 'true', 'enctype' => 'multipart\form-data']) !!}
                {!! csrf_field() !!}
                @include('haulers.form')
                {!! Form::close() !!}
                
            </div><!-- end row -->


@endsection