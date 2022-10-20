<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
class Lease extends Model
{
    use HasFactory, LogsActivity;
  
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected static $logName ='Leases';
    protected static $logOnlyDirty = true;
    protected $fillable = [
        'lease_date','lease_end','project_id', 'unit_id','lease_type','resident_id','status_of_account','amenity'
    ];

    public function building()
    {
        return $this->belongsTo(Project::class,'project_id','id');
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class,'unit_id');
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class,'resident_id');
    }
  
    public function residents()
    {
        return $this->hasMany(LeaseResident::class,'lease_id')->orderBy('id','ASC');
    }
    public function documents()
    {
        return $this->hasOne(LeaseDetail::class,'lease_id');
    }
    public function amenities()
    {
        return $this->hasMany(LeaseAmenity::class,'lease_id');
    }
   
}