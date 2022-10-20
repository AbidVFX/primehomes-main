<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Unit;
use Spatie\Activitylog\Traits\LogsActivity;
class Project extends Model
{
    use HasFactory, LogsActivity;
  
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    
    protected static $logName ='Buildings';
    protected static $logOnlyDirty = true;
    protected $fillable = [
        'building_id','name', 'address','phase','image','association_dues','due_days','rules'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}