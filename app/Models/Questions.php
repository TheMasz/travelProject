<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    use HasFactory;
    protected $primaryKey = 'questions_id';
    protected $fillable = ['question_text'];
    public $timestamps = false;
    
}


