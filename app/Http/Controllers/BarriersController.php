<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Card;
use App\Cardholder;
use App\Log;
use App\Driver;
use Carbon\Carbon;
use App\Serve;
use App\Shipment;

class BarriersController extends Controller
{

    // Capture All RFIC Cards that has not a driver CARD
    public function barrierNoDriver()
    {
        $pickup_cards = Cardholder::select('CardholderID')
        ->where('FirstName', 'LIKE', '%pickup%')
        ->pluck('CardholderID'); 

        $guard_cards = Cardholder::select('CardholderID')
        ->where('FirstName', 'LIKE', '%GUARD%')
        ->pluck('CardholderID'); 

        $executive_cards = Cardholder::select('CardholderID')
        ->where('FirstName', 'LIKE', '%EXECUTIVE%')
        ->pluck('CardholderID'); 

        // Remove all cardholder without driver assigned
        $not_driver = array_collapse([$pickup_cards, $guard_cards, $executive_cards]);
        
        return $not_driver;
    }

    // check the direction of the barrier
    public function getBarrierDirection($door, $cardholder, $direction)
    {
        // All Plant in 
        $barrier_in = Log::select('CardholderID','Direction','LocalTime')
        ->where('CardholderID',$cardholder)
        ->where('DoorID',$door)
        ->whereNotIn('CardholderID',$this->barrierNoDriver())
        ->where('CardholderID', '>=', 15)
        ->where('Direction', $direction)
        ->orderBy('LocalTime','DESC')
        ->first();

        if(empty($barrier_in)) {
            $x = null;
        } else {
            $x = $barrier_in->LocalTime;
        }

        return $x;
    }

    public function getBarrierLocation($door, $controller)
    {
        $barriers = Log::select('LogID','CardholderID','LocalTime')
        ->whereDate('LocalTime',Carbon::today())
        ->where('DoorID',$door)
        ->where('ControllerID', $controller)
        ->whereNotIn('CardholderID',$this->barrierNoDriver())
        ->where('CardholderID', '>=', 15)
        ->orderBy('LocalTime','DESC')
        ->with('driver','driver.image','driver.truck','driver.hauler','shipment')
        ->first();

        return $barriers;
    }

    //API Functions
    public function laPazAPI()
    {
        // Get Logs from Lapaz Barrier RFID
        $lapaz_drivers = $this->getBarrierLocation(0,5);
        return $lapaz_drivers;
    }

    public function manilaAPI()
    {
       // Get Logs from Lapaz Barrier RFID
        $manila_drivers = $this->getBarrierLocation(3,2);
        return $manila_drivers;
    }

    public function bataanAPI()
    {
         // Get Logs from Bataan Barrier RFID
        $bataan_drivers =  $this->getBarrierLocation(0,9);
        return $bataan_drivers;
    }

    // View Functions
    public function laPazArea()
    {
        return view('locations.lapaz');
    }

    public function manilaArea()
    {
        return view('locations.manila');
    }

    public function bataanArea()
    {
        return view('locations.bataan');
    }

}
