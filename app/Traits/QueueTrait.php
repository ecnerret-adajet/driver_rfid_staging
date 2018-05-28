<?php

namespace App\Traits;

use App\QueueEntry;
use App\GateEntry;
use App\Shipment;
use Carbon\Carbon;
use App\Log;

trait QueueTrait {

    public function checkIfExist($driverqueue_id) {

        $totalEntry = QueueEntry::whereDate('created_at',Carbon::today())
                        ->where('driverqueue_id',$driverqueue_id)
                        ->doesntHave('shipment')
                        ->whereNotNull('driver_availability')
                        ->whereNotNull('truck_availability')
                        ->whereNotNull('isTappedGateFirst')
                        ->where('isDRCompleted', '!=' ,"0000-00-00")
                        ->pluck('CardholderID')
                        ->unique()
                        ->count();
        
        // $lastNumber = QueueEntry::whereDate('created_at',Carbon::today())
        //                         ->where('driverqueue_id',$driverqueue_id)
        //                         ->whereNotNull('driver_availability')
        //                         ->whereNotNull('truck_availability')
        //                         ->whereNotNull('isTappedGateFirst')
        //                         ->where('isDRCompleted', '!=' ,"0000-00-00")
        //                         ->orderBy('id','DESC')
        //                         ->first()
        //                         ->queue_number;

        // $compute = !empty($lastNumber) ? $lastNumber + 1 : 1;
    
        // return $compute == $lastNumber ? $lastNumber : $compute;

        return $totalEntry + 1;
    
    }

    public function checkIfReturned($cardholder) {

        $hasShipment = Shipment::where('created_at', '>', Carbon::now()->subHours(24)->toDateTimeString())
                            ->where('CardholderID',$cardholder)
                            ->whereNotNull('status')
                            ->orderBy('created_at','DESC')
                            ->pluck('shipment_number')
                            ->count(); 

        return $hasShipment;
    }

}