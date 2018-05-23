<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QueueEntry extends Model
{
    protected $table = 'queue_entries';

    protected $connection = "sqlsrv";

    protected $fillable = [
        'queue_number',
        'driver_name',
        'avatar',
        'truck_id',
        'plate_number',
        'hauler_name',
        'driverqueue_id',
        'shipment_number',
        'LogID',
        'CardholderID',
        'LocalTime',
        'isDRCompleted',
        'driver_availability',
        'truck_availability',
        'isTappedGateFirst',
        'isSecondDelivery',
        'isShipmentStarted',
    ];

    protected $hidden = [
        'updated_at',
        'truck_id',
        'isShipmentStarted'
    ];

    public function getDates()
    {
        return [];
    }

    // Relationships Model

    public function driver() {
        return $this->belongsTo(Driver::class);
    }

    public function truck() {
        return $this->belongsTo(Truck::class);
    }

    public function hauler() {
        return $this->belongsTo(Hauler::class);
    }

    public function shipment() {
        return $this->belongsTo(Shipment::class,'shipment_number','shipment_number');
    }

    public function log() {
        return $this->belongsTo(Log::class, 'LogID','LogID');
    }

    public function cardholder() {
        return $this->belongsTo(Cardholder::class, 'CardholderID','CardholderID');
    }

    public function driverqueue() {
        return $this->belongsTo(Driverqueue::class);
    }

    public function gateEntry() {
        return $this->belongsTo(GateEntry::class);
    }


}
