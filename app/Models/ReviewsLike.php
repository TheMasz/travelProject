<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewsLike extends Model
{
    protected $table = "reviews_like";
    public $timestamps = false;
    use HasFactory;
}
