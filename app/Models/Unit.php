<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
class Unit extends Model
{
    use HasFactory, LogsActivity;
  
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected static $logName ='Units';
    protected static $logOnlyDirty = true;
    protected $fillable = [
        'unit_no', 'unit_type','floor_area','parking','slot_no','parking_area','unit_paid','parking_location','project_id','owner_id','water_meter_number'
    ];

    public function building()
    {
        return $this->belongsTo(Project::class,'project_id','id');
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class,'owner_id');
    }

    public function lease(){
        return $this->hasOne(Lease::class,'unit_id','id');
    }
}