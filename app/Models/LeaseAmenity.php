<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaseAmenity extends Model
{
    use HasFactory;
    protected $fillable = [
        'lease_id','amenity_id'
    ];

   
}
