<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaseResident extends Model
{
    use HasFactory;
    protected $fillable = [
        'lease_id','resident_name', 'resident_relation','resident_information'
    ];

    
}
