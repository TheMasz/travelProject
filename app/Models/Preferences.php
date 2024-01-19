<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preferences extends Model
{
    use HasFactory;
    protected $primaryKey = 'preference_id';
    protected $fillable = ['preference_name'];
    public $timestamps = false;
    
}


