@extends('layouts.online')
@section('content')


     <div class="card mx-auto mb-3">
        <div class="card-header">
         Edit User

         <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
            Back
         </a>
        </div>
        <div class="card-body">
        
            {!! Form::model($user, ['method' => 'PATCH','route' => ['haulerUsers.update', $user->id], 'enctype'=>'multipart/form-data']) !!}
            {!! csrf_field() !!}
              <form>
             @include('haulerOnline.hauler-form')
             </form>
            {!! Form::close() !!}
                    
        </div>
      </div>

    </div>
    </div><!-- end row -->

@endsection