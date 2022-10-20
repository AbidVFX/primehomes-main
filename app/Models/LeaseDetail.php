<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaseDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'lease_id','lease_document1', 'lease_document2','lease_document3','lease_document4'
    ];

   
}
