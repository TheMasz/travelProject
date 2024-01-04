<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'location_id';
    
    protected $fillable = [
        'location_id',
        'location_name',
        'address',
        'detail',
        's_time',
        'e_time',
        'province_id',
        'latitude',
        'longitude',
    ];

    public $timestamps = false;

    public function images()
    {
        return $this->hasMany(LocationImages::class, 'location_id', 'location_id');

    } 
        
}
