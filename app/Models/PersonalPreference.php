<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalPreference extends Model
{
    use HasFactory;
    protected $table = 'personal_preference';
    public $timestamps = false;
    
}
