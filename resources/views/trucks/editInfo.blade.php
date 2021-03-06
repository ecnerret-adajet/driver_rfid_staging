@extends('layouts.app')
@section('content')

<div class="card mx-aut mb-3">
        <div class="card-header">
        Truck's Information

        <a class="btn btn-primary btn-sm pull-right" href="{{ URL::previous() }}">
        Back
        </a>
        </div>
        <div class="card-body">

            <div class="row">

                <div class="col-sm-2">

                       <span class="fa-stack fa-lg display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-circle fa-stack-2x"></i>
                            <i class="fa fa-truck fa-stack-1x fa-inverse" aria-hidden="true"></i>
                        </span>               
                
                </div>

                <div class="col-sm-4">
                    <span class="text-muted">PLATE NUMBER:</span><br/>
                        @if($truck->plate_number == null)
                            {{  $truck->reg_number }}
                        @else
                            {{  $truck->plate_number }}
                        @endif                        
                    <br/> 
                    <br/>
                    <span class="text-muted">DRIVER NAME:</span><br/>
                        @if(!count($truck->drivers) == 0)
                            {{  $truck->drivers->first()->name }}
                        @else
                            NO DRIVER
                        @endif
                    <br/>
                    <br/>
                    <span class="text-muted">VENDOR:</span><br/>
                     @if(!count($truck->haulers) == 0)
                            {{ $truck->hauler->name }}
                    @else
                        {{ $search->haulerName($truck->vendor_description) }}
                    @endif
                    
                </div>

                <div class="col-sm-4">

                    <span class="text-muted">SUBVENDOR:</span><br/>
                        @if(!count($truck->haulers) == 0)
                            {{ $truck->hauler->name }}
                        @else
                            {{ $search->haulerName($truck->subvendor_description) }}
                        @endif

                    <br/>
                    <br/>
                    <span class="text-muted">START VALIDITY DATE</span><br/>
                    {{ date('F, d Y',strtotime($truck->validity_start_date)) }}
                    <br/>
                    <br/>
                    <span class="text-muted">END VALIDITY DATE</span><br/>
                    {{ date('F, d Y',strtotime($truck->validity_end_date)) }}

             
                
                </div>
                <div class="col-sm-2">
                    <span class="text-muted">STATUS</span><br/>
                        @if($truck->availability == 1)
                            <span class="badge badge-primary">
                                ACTIVE
                            </span>
                        @else
                            <span class="badge badge-warning">
                                INACTIVE
                            </span>
                        @endif
                </div>  

            </div>


        </div><!-- end card-body -->
    </div> <!-- end card -->

          <div class="row">
                <div class="col-sm-12">
                 <div class="card mx-auto">
                    <div class="card-header">
                        Edit Truck


                    </div>
                    <div class="card-body" id="form-truck">
                      


                    {!! Form::model($truck, ['method' => 'PATCH','route' => ['trucks.updateInfo', $truck->id], 'enctype'=>'multipart/form-data']) !!}
                    {!! csrf_field() !!}

                      <form>
                       @include('trucks.formInfo')
                    </form>
                            
                    {!! Form::close() !!}
                            
                   

                 
                </div>
                </div>
                </div>
            </div><!-- end row -->

@endsection