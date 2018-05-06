<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Capacity extends Model
{
    protected $fillable = [
        'description',
        'capacity',
    ];

    public function getDates()
    {
        return [];
    }

    public function truck()
    {
        return $this->hasOne('App\Truck');
    }
}
