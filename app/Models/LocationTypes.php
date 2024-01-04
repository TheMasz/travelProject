<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationTypes extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'type_id',
        'location_id',
        'preference_id',
    ];
}
