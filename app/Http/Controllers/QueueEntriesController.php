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

        $queues = QueueEntry::where('driverqueue_id',$driverqueue_id)
                            ->whereNotIn('CardholderID',$checkTruckscaleOut->values()->all())
                             ->where('created_at', '>=', Carbon::today())
                            // ->whereNull('shipment_number')
                            ->doesntHave('shipment')
                            ->whereNotNull('driver_availability')
                            ->whereNotNull('truck_availability')
                            ->where('isDRCompleted','NOT LIKE','%0000-00-00%')
                            ->whereNotNull('isTappedGateFirst')
                            ->orderBy('LocalTime','ASC')
                            ->get()
                            ->unique('CardholderID');
 
        return $queues->values()->all();
    }

    // Show all queue to feed planner's monitoring
    public function getQueueEntriesFeed($driverqueue_id) 
    {
        // $checkTruckscaleOut = collect(Log::truckscaleOutFromQueue($driverqueue_id))->unique();

        $queues = QueueEntry::with('truck','truck.plants','truck.capacity','shipment')
                            ->whereDate('created_at',Carbon::today())
                            ->where('driverqueue_id',$driverqueue_id)
                            // ->whereNotIn('CardholderID',$checkTruckscaleOut->values()->all())
                            ->whereNotNull('driver_availability')
                            ->whereNotNull('truck_availability')
                            ->where('isDRCompleted','NOT LIKE','%0000-00-00%')
                            ->whereNotNull('isTappedGateFirst')
                            ->orderBy('LocalTime','ASC')
                            ->get()
                            ->unique('CardholderID');

        return $queues->values()->all();
    }

    //Search queue entries by date
    public function searchQueueEntriesFeed(Request $request, Driverqueue $driverqueue)
    {
        $this->validate($request, [
            'search_date' => 'required'
        ]);

        $search_date = $request->get('search_date');

        // $checkTruckscaleOut = collect(Log::truckscaleOutFromQueue($driverqueue_id))->unique();

        $queues = QueueEntry::with('truck','truck.plants','truck.capacity','shipment')
                            ->where('driverqueue_id',$driverqueue->id)
                            ->whereDate('LocalTime',Carbon::parse($search_date))
                            // ->whereNotIn('CardholderID',$checkTruckscaleOut->values()->all())
                            ->whereNotNull('driver_availability')
                            ->whereNotNull('truck_availability')
                            ->where('isDRCompleted','NOT LIKE','%0000-00-00%')
                            ->whereNotNull('isTappedGateFirst')
                            ->orderBy('LocalTime','ASC')
                            ->get()
                            ->unique('CardholderID');

        return $queues->values()->all();


    }

    //Count Queue Statuses
    public function getQueueStatus($driverqueue)
    {
        $totalAssigned = QueueEntry::totalAssigned($driverqueue)->count();
        $totalOpen = QueueEntry::totalOpen($driverqueue)->count();
        $current_in_plant = count($this->getQueueEntriesFeed($driverqueue));

        $data = array(
            'totalAssigned' => $totalAssigned,
            'totalOpen' => $totalOpen,
            'current_in_plant' => $current_in_plant
        );

        return $data;

        // return $totalAssigned = QueueEntry::totalAssigned($driverqueue);

    }

    //Get the last tapped driver to planners monitoring
    public function lastDriverTapped($driverqueue) 
    {
        $lastDriverTapped = QueueEntry::orderBy('id','DESC')
                        ->whereNotNull('shipment_number')
                        ->where('driverqueue_id',$driverqueue)
                        ->first();

        return $lastDriverTapped;
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
                        

        $queueEntry = QueueEntry::updateOrCreate(
            [
                'LogID' => $lastLogEntry->LogID,   
            ],
            [
                'CardholderID' => $lastLogEntry->CardholderID,
                'driver_name' => $lastLogEntry->driver->name,
                'plate_number' => !empty($lastLogEntry->driver->truck) ? $lastLogEntry->driver->truck->plate_number : null,
                'hauler_name' => !empty($lastLogEntry->driver->hauler) ? $lastLogEntry->driver->hauler->name : null,
                'shipment_number' => Shipment::checkIfShipped($lastLogEntry->CardholderID,null)->first(),
                'queue_number' => $this->checkIfExist($driverLocation->id),
                // 'queue_number' => $this->checkEntry($lastLogEntry->CardholderID, $this->checkIfExist($driverLocation->id)) ? $this->checkIfExist($driverLocation->id) + 1 : $this->checkIfExist($driverLocation->id),
                'isDRCompleted' =>  !empty($lastLogEntry->driver->truck) ? Truck::callLastTrip($lastLogEntry->driver->truck->plate_number) : null,
                'isTappedGateFirst' => !empty(GateEntry::checkIfTappedFromGate($lastLogEntry->CardholderID)) ? 1 : null,
                'isSecondDelivery' => $this->checkIfReturned($lastLogEntry->CardholderID) > 0 ? 1 : 0,
                'driver_availability' => !empty($lastLogEntry->driver) && $lastLogEntry->driver->availability == 1 ? 1 : null,
                'truck_availability' => !empty($lastLogEntry->driver->truck) && $lastLogEntry->driver->truck->availability == 1 ? 1 : null,
                'isShipmentStarted' => 0,
                'truck_id' => !empty($lastLogEntry->driver->truck) ? $lastLogEntry->driver->truck->id : null,
                'avatar' => !empty($lastLogEntry->driver->image) ? $lastLogEntry->driver->image->avatar : $lastLogEntry->driver->avatar,
                'driverqueue_id' => $driverLocation->id,
                'LocalTime' => $lastLogEntry->LocalTime,
            ]
        );
        
        if($queueEntry->wasRecentlyCreated == true) {
            event(new QueueEntryEvent($queueEntry,$driverLocation));
            return $queueEntry;
        } else {
            $queueLast = QueueEntry::where('driverqueue_id',$driverLocation->id)->orderBy('id','DESC')->first();
            return $queueLast;
            //  return $queueEntry;
        }   
            
            // return $queueEntry;
    }

    //Display queue entry by location
    public function queueEntry(Driverqueue $driverqueue)
    {
        return view('queueEntries.show',compact('driverqueue'));
    }

    //Display queue from planners monitoring
    public function queueEntryFeed()
    {
        $driverqueues = Driverqueue::all();

        return view('queueEntries.index',compact('driverqueues'));
    }


}
