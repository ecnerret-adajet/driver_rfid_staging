<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Log;
use App\Shipment;
use App\Driverqueue;

class ShipmentsController extends Controller
{
    // Get all assigned shipment based from location queue

    public function servedToday(Driverqueue $driverqueue)
    {
        //Get all drivers from location
        $located_serves = Shipment::where('ControllerID', $driverqueue->controller)
                            ->where('DoorID',$driverqueue->door)
                            ->whereDate('created_at','>=', Carbon::now()->subDay())
                            ->pluck('CardholderID')
                            ->unique();

        // get the cardholder with time out
        $log = Log::whereIn('CardholderID',$located_serves->values()->all())
                ->whereDate('LocalTime','>=', Carbon::now()->subDay())
                ->where('Direction',2) 
                ->pluck('CardholderID')
                ->unique();
        
        $served = Shipment::with('driver','driver.truck','driver.hauler','driver.image')
                        ->whereDate('created_at','>=', Carbon::now()->subDay())
                        ->where('ControllerID', $driverqueue->controller)
                        ->where('DoorID',$driverqueue->door)
                        ->whereNotIn('CardholderID',$log->values()->all())
                        ->orderBy('id','DESC')
                        ->get()
                        ->unique('CardholderID');

        return $served->values()->all();

    }

    // Get the last shipment assigned based from location queue

    public function currentlyServing(Driverqueue $driverqueue) 
    {
        $last_served = Shipment::with('driver','driver.truck','driver.hauler','driver.image')
                        ->orderBy('id','DESC')
                        ->where('DoorID',$driverqueue->door)
                        ->where('ControllerID',$driverqueue->controller)
                        ->take(1)
                        ->get();

        return $last_served;
    }
}
