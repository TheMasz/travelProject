<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaysOpening extends Model
{
    use HasFactory;
    protected $table = 'daysopening';
    public $timestamps = false;
    protected $fillable = [
        'opening_id',
        'location_id',
        'day_id',
    ];
}
