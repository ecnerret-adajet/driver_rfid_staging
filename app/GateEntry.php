<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GateEntry extends Model
{
    protected $table = 'gate_entries';

     protected $connection = "sqlsrv";

    protected $fillable  = [
        'gate_number',
        'driver_name',
        'avatar',
        'plate_number',
        'hauler_name',
        'driverqueue_id',
        'LogID',
        'CardholderID',
        'shipment_number',
        'isShipmentStarted',
        'LocalTime',
        'driver_availability',
        'truck_availability',
    ];

    // Relationships Tables

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

}

