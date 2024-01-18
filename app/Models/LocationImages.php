<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationImages extends Model
{
    use HasFactory;
    protected $primaryKey = 'img_id';
    protected $fillable = ['img_id','img_path','location_id','credit'];
    public $timestamps = false;
    



}
