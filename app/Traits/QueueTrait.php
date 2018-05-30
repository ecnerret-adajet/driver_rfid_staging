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
                        // ->doesntHave('shipment')
                        // ->whereNull('shipment_number')
                        // ->whereNotNull('driver_availability')
                        // ->whereNotNull('truck_availability')
                        // ->whereNotNull('isTappedGateFirst')
                        // ->where('isDRCompleted', '!=' ,"0000-00-00")
                        ->pluck('CardholderID')
                        ->unique('CardholderID')
                        ->count();

        return $totalEntry + 1;
    
    }

    /**
     * Returns boolean value
     */
    public function checkEntry($queue, $cardholder) {

        $search_queue = QueueEntry::whereDate('created_at',Carbon::today())
                    ->where('CardholderID', $cardholder)
                    ->where('queue_number', $queue)
                    ->exists();

        return $search_queue;

    }

    public function checkIfReturned($cardholder) {

        $hasShipment = Shipment::where('created_at',  '>=', Carbon::now()->subHours(24)->toDateTimeString())
                            ->where('CardholderID',$cardholder)
                            ->whereNotNull('status')
                            ->orderBy('created_at','DESC')
                            ->pluck('shipment_number')
                            ->count(); 

        return $hasShipment;
    }

}