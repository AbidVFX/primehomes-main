<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id', 'unit_no','month','year','payable'
    ];

    public function billing_detail()
    {
        return $this->hasMany(BillingDetail::class,'billing_id');
    }
    public function building()
    {
        return $this->belongsTo(Project::class,'project_id','id');
    }

    public function unitowner()
    {
        return $this->belongsTo(Unit::class, 'unit_no','id','project_id', 'project_id');
    }

    public function leaseto()
    {
        return $this->belongsTo(Lease::class, 'unit_no','unit_id','project_id', 'project_id');
    }
}
