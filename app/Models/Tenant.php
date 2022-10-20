<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
class Tenant extends Model
{
    use HasFactory, LogsActivity;
  
    /**
     * The attributes that are mass assignable.
     *	
     * @var array
     */
    protected static $logAttributes = ['firstname', 'lastname','middlename','landline', 'primary_mobile','secondary_mobile','primary_email','secondary_email','alternate_email','password','contact_person','contact_number'];
    protected static $logName ='Tenants';
    protected static $lonOnlyDirty = true;
    protected $fillable = [
        'firstname', 'lastname','middlename','landline', 'primary_mobile','secondary_mobile','primary_email','secondary_email','alternate_email','password','contact_person','contact_number'
    ];
}