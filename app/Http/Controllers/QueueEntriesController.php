<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\NotDriverTrait;
use App\Traits\QueueTrait;
use App\Events\QueueEntryEvent;
use App\Driverqueue;
use App\QueueEntry;
use App\GateEntry;
use App\Truck;
use App\Driver;
use App\Log;
use App\Cardholder;
use Carbon\Carbon;
use App\Shipment;
use Auth;
use DB;

class QueueEntriesController extends Controller
{
    use NotDriverTrait, QueueTrait;

    public function __construct()
    {
        $this->notDriver();    
    }

    // Show all recently queue
    public function getQueueEntries($driverqueue_id) 
    {   
        $checkTruckscaleOut = collect(Log::truckscaleOutFromQueue($driverqueue_id))->unique();

        $queues = QueueEntry::with('driver','driver.image','truck','hauler')
                            ->where('driverqueue_id',$driverqueue_id)
                            ->whereNotIn('CardholderID',$checkTruckscaleOut->values()->all())
                            ->whereNotNull('driver_availability')
                            ->whereNotNull('truck_availability')
                            ->where('isDRCompleted','NOT LIKE','%0000-00-00%')
                            ->whereNotNull('isTappedGateFirst')
                            ->orderBy('LocalTime','DESC')
                            ->get()
                            ->unique('CardholderID');
 
        return $queues->values()->all();
    }

    // Store new queue entry
    public function storeQueueEntries(Request $request, $driverqueue_id) 
    {

        $driverLocation = Driverqueue::where('id',$driverqueue_id)->first();

        $lastLogEntry = Log::where('DoorID',$driverLocation->door)
                        ->where('ControllerID', $driverLocation->controller)
                        ->whereNotIn('CardholderID',$this->notDriver())
                        ->where('CardholderID', '>=', 15)
                        ->orderBy('LocalTime','DESC')
                        ->with('driver','driver.image','driver.truck','driver.hauler','shipment')
                        ->first();
        
        $totalEntry = QueueEntry::whereDate('created_at',Carbon::today())->where('driverqueue_id',$driverLocation->id)->count();         
        
        $queueEntry = QueueEntry::updateOrCreate(
            [
                'shipment_number' => Shipment::checkIfShipped($lastLogEntry->CardholderID,null)->first(),
                'isDRCompleted' =>  !empty($lastLogEntry->driver->truck) ? Truck::callLastTrip($lastLogEntry->driver->truck->plate_number) : null,
                'isTappedGateFirst' => !empty(GateEntry::checkIfTappedFromGate($lastLogEntry->CardholderID)) ? 1 : null,
                'isSecondDelivery' => $this->checkIfReturned($lastLogEntry->CardholderID) > 0 ? 1 : 0,
                'driver_availability' => !empty($lastLogEntry->driver) && $lastLogEntry->driver->availability == 1 ? 1 : null,
                'truck_availability' => !empty($lastLogEntry->driver->truck) && $lastLogEntry->driver->truck->availability == 1 ? 1 : null,
                'isShipmentStarted' => 0,
            ],
            [
                'queue_number' =>  $this->checkIfExist($driverLocation->id) == 0 ? 1 : $this->checkIfExist($driverLocation->id) + 1,
                'LogID' => $lastLogEntry->LogID,
                'CardholderID' => $lastLogEntry->CardholderID,
                'driver_name' => $lastLogEntry->driver->name,
                'plate_number' => !empty($lastLogEntry->driver->truck) ? $lastLogEntry->driver->truck->plate_number : null,
                'truck_id' => !empty($lastLogEntry->driver->truck) ? $lastLogEntry->driver->truck->id : null,
                'avatar' => !empty($lastLogEntry->driver->image) ? $lastLogEntry->driver->image->avatar : $lastLogEntry->driver->avatar,
                'hauler_name' => !empty($lastLogEntry->driver->hauler) ? $lastLogEntry->driver->hauler->name : null,
                'driverqueue_id' => $driverLocation->id,
                'LocalTime' => $lastLogEntry->LocalTime,
            ]
        );


        if($queueEntry->wasRecentlyCreated == true) {

            event(new QueueEntryEvent($queueEntry,$driverLocation));
            
            return $queueEntry;

        } else {

            return $queueEntry;

        }
                     
    }

    //Display queue entry by location
    public function queueEntry(Driverqueue $driverqueue)
    {
        return view('queueEntries.show',compact('driverqueue'));
    }


}
