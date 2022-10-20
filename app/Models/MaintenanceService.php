<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceService extends Model
{
    use HasFactory;
    protected $fillable = [
        'Timeframe',
        'PurposeOfMaintenance',
        'DateOfMaintenance',
        'ReportType',
        'email',
    ];
}
