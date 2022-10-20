<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Owner extends Model
{
    use HasFactory, LogsActivity;
  
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
   
    protected static $logName ='Owners';
    protected static $logOnlyDirty = true;
    
    protected $fillable = [
        'title','type','firstname', 'lastname','middlename','landline', 'primary_mobile','secondary_mobile','primary_email','secondary_email','alternate_email','password','contact_person','contact_number','valid_id','other_document'
    ];
}