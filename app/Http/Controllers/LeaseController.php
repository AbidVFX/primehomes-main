<?php

namespace App\Http\Controllers;

use File;
use App\Models\Unit;
use App\Models\Lease;
use App\Models\Owner;
use App\Models\Project;
use App\Models\Amenitie;
use App\Models\LeaseDetail;
use Illuminate\Support\Str;
use App\Models\LeaseAmenity;
use Illuminate\Http\Request;
use App\Models\LeaseResident;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LeaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:lease-list|lease-create|lease-edit|lease-delete', ['only' => ['index','show']]);
        $this->middleware('permission:lease-create', ['only' => ['create','store']]);
        $this->middleware('permission:lease-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:lease-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leases = Lease::with('documents', 'amenities', 'residents')->latest()->get();
        // echo "<pre>"; print_r($leases); exit;
        $authRole = auth()->user()->getRoleNames()[0];

 if ($authRole == 'Superadmin') {
            $notifications = DB::table('notifications')->where('read_at', null)->latest()->get();
        } elseif ($authRole == 'Owner') {
            $notifications = DB::table('notifications')->where('owner_id', auth()->user()->id)->where('status', '!=', 'pending')->where('owner_read_at', '=', null)->latest()->get();
        } elseif ($authRole == 'Tenant') {
            $notifications = DB::table('notifications')->where('owner_id', auth()->user()->id)->where('status', '!=', 'pending')->where('owner_read_at', '=', null)->latest()->get();
        } else {
        }

        return view('leases.index', compact('leases', 'notifications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = Project::latest()->get();
        $amenities = Amenitie::latest()->get();
        $authRole = auth()->user()->getRoleNames()[0];

 if ($authRole == 'Superadmin') {
            $notifications = DB::table('notifications')->where('read_at', null)->latest()->get();
        } elseif ($authRole == 'Owner') {
            $notifications = DB::table('notifications')->where('owner_id', auth()->user()->id)->where('status', '!=', 'pending')->where('owner_read_at', '=', null)->latest()->get();
        } elseif ($authRole == 'Tenant') {
            $notifications = DB::table('notifications')->where('owner_id', auth()->user()->id)->where('status', '!=', 'pending')->where('owner_read_at', '=', null)->latest()->get();
        } else {
        }

        return view('leases.create', compact('projects', 'amenities', 'notifications'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
       
        //echo "<pre>"; print_r($request->addmore); exit;
        request()->validate([
            'lease_date' => 'required',
            'lease_end' => 'required',
            'project_id' => 'required',
            'unit_id' => 'required|unique:leases,unit_id',
            'lease_type'=> 'required',
            'resident_id' => 'required',
            'status_of_account' => 'required',
            'amenity' => 'required',
            'amenities' => 'required_if:amenity,Y',
            'filenames.*' => 'mimes:png,jpeg,jpg,doc,pdf,docx,xlx'

        ]);
        
        if (Lease::where('resident_id', '=', $request->resident_id)->where('unit_id', '=', $request->unit_id)->where('project_id', '=', $request->project_id)->exists()) {
            return redirect()->back()
            ->with('destroy', 'Duplicate Lease against Unit No: '.$request->unit_no.'');
        }
        $leasedata = array(
            'lease_date' =>$request->lease_date,
            'lease_end' =>$request->lease_end,
            'project_id' =>$request->project_id,
            'unit_id'=>$request->unit_id,
            'lease_type'=>$request->lease_type,
            'resident_id'=>$request->resident_id,
            'status_of_account'=>$request->status_of_account,
            'amenity'=>$request->amenity,
           
        );
     
        
        $leaseid = Lease::create($leasedata);
        //adding amenities
        if ($request->amenity=='Y') {
            if ($request->amenities) {
                foreach ($request->amenities as $amenity) {
                    LeaseAmenity::create(['lease_id'=>$leaseid->id,'amenity_id'=>$amenity]);
                }
            }
        }
        //if files are uploaded
        if ($request->hasfile('filenames')) {
            $data['lease_id'] = $leaseid->id;
            foreach ($request->file('filenames') as $key=>$file) {
                $name = $file->getClientOriginalName();
                $destinationPath = public_path('lease_documents/'.$leaseid->id);
                if (File::isDirectory($destinationPath)) {
                    $file->move($destinationPath, $name);
                } else {
                    File::makeDirectory($destinationPath, 0777, true, true);
                    $file->move($destinationPath, $name);
                }
                $data[$key] = $name;
            }
            
            LeaseDetail::create($data);
        }
      
        //adding residents
        if (count($request->addmore)>0) {
            foreach ($request->addmore as $key=>$resident) {
                if ($resident['resident_name']!="" && $resident['resident_relation']!="") {
                    $imagename ='';
            
                    if (is_file($resident['resident_information'])) {
                        $file = $resident['resident_information'];
                        $imagename = $file->getClientOriginalName();
                        //$imagename = Str::slug( $imagename, '-');
                        $destinationPath = public_path('lease_relation/'.$leaseid->id);
                        if (File::isDirectory($destinationPath)) {
                            $file->move($destinationPath, $imagename);
                        } else {
                            File::makeDirectory($destinationPath, 0777, true, true);
                            $file->move($destinationPath, $imagename);
                        }
                    }
                    $residentsdata = array(
                    'lease_id' => $leaseid->id,
                    'resident_name' => $resident['resident_name'],
                    'resident_relation' => $resident['resident_relation'],
                    'resident_information' => $imagename,
                );
                    LeaseResident::create($residentsdata);
                }
            }
        }
        
        return redirect()->route('leases.index')
                        ->with('success', 'Lease created successfully.');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Lease  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Lease $lease)
    {
        //dd($lease->unit->unit_no);
        $projects = Project::latest()->get();
        $amenities = Amenitie::latest()->get();
        $authRole = auth()->user()->getRoleNames()[0];

 if ($authRole == 'Superadmin') {
            $notifications = DB::table('notifications')->where('read_at', null)->latest()->get();
        } elseif ($authRole == 'Owner') {
            $notifications = DB::table('notifications')->where('owner_id', auth()->user()->id)->where('status', '!=', 'pending')->where('owner_read_at', '=', null)->latest()->get();
        } elseif ($authRole == 'Tenant') {
            $notifications = DB::table('notifications')->where('owner_id', auth()->user()->id)->where('status', '!=', 'pending')->where('owner_read_at', '=', null)->latest()->get();
        } else {
        }

        return view('leases.edit', compact('lease', 'projects', 'amenities', 'notifications'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Lease  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lease $lease)
    {
        request()->validate([
            'lease_date' => 'required',
            'project_id' => 'required',
            'unit_id' => 'required',
            'lease_type'=> 'required',
            'resident_id' => 'required',
            'status_of_account' => 'required',
            'amenity' => 'required',
            'amenities' => 'required_if:amenity,Y',
            'filenames.*' => 'mimes:png,jpeg,jpg,doc,pdf,docx,xlx'

        ]);
        if (Lease::where('id', '!=', $lease->id)->where('resident_id', '=', $request->resident_id)->where('unit_id', '=', $request->unit_id)->where('project_id', '=', $request->project_id)->exists()) {
            return redirect()->back()
            ->with('destroy', 'Duplicate Lease against Unit No: '.$request->unit_no.'');
        }
        $leasedata = array(
            'lease_date' =>$request->lease_date,
            'lease_end' =>$request->lease_end,
            'project_id' =>$request->project_id,
            'unit_id'=>$request->unit_id,
            'lease_type'=>$request->lease_type,
            'resident_id'=>$request->resident_id,
            'status_of_account'=>$request->status_of_account,
            'amenity'=>$request->amenity,
           
        );
       
        
        $lease->update($leasedata);
        //adding amenities
        LeaseAmenity::where('lease_id', $lease->id)->delete();
        if ($request->amenity=='Y') {
            if ($request->amenities) {
                foreach ($request->amenities as $amenity) {
                    LeaseAmenity::create(['lease_id'=>$lease->id,'amenity_id'=>$amenity]);
                }
            }
        }
        //if files are uploaded
        
       
        if ($request->hasfile('filenames')) {
            LeaseDetail::where('lease_id', $lease->id)->delete();
            $data['lease_id'] = $lease->id;
            foreach ($request->file('filenames') as $key=>$file) {
                $name = $file->getClientOriginalName();
                $destinationPath = public_path('lease_documents/'.$lease->id);
                if (File::isDirectory($destinationPath)) {
                    $file->move($destinationPath, $name);
                } else {
                    File::makeDirectory($destinationPath, 0777, true, true);
                    $file->move($destinationPath, $name);
                }
               
                $data[$key] = $name;
                //dd($data);
            }
            LeaseDetail::create($data);
        }
      
        //adding residents
        //LeaseResident::where('lease_id',$lease->id)->delete();
        if (count($request->addmore)>0) {
            //print_r($request->addmore); exit;
            LeaseResident::whereNotIn('id', $request->resident_ids)->delete();
            foreach ($request->addmore as $key=>$resident) {
                if ($resident['resident_name']!="" && $resident['resident_relation']!="") {
                    $residentsdata = array(
                    'lease_id' => $lease->id,
                    'resident_name' => $resident['resident_name'],
                    'resident_relation' => $resident['resident_relation']
                );
                    if (isset($resident['resident_information']) && is_file($resident['resident_information'])) {
                        $file = $resident['resident_information'];
                        $imagename = $file->getClientOriginalName();
                        $destinationPath = public_path('lease_relation/'.$lease->id);
                        if (File::isDirectory($destinationPath)) {
                            $file->move($destinationPath, $imagename);
                        } else {
                            File::makeDirectory($destinationPath, 0777, true, true);
                            $file->move($destinationPath, $imagename);
                        }
                        $residentsdata['resident_information'] = $imagename;
                    }
                
                    $matchThese = ['id'=>$request->resident_ids[$key],'lease_id'=>$lease->id];
                    $resident = LeaseResident::updateOrCreate($matchThese, $residentsdata);
                }
            }
        }
        return redirect()->route('leases.index')
                        ->with('success', 'Lease updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Lease  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lease $lease)
    {
        $lease->delete();
        LeaseAmenity::where('lease_id', $lease->id)->delete();
        LeaseDetail::where('lease_id', $lease->id)->delete();
        return redirect()->route('leases.index')
                        ->with('success', 'Lease deleted successfully');
    }

    public function fetch_units(Request $request)
    {
        $unitsinfo = Unit::where('project_id', $request->project_id)->where('owner_id', '<>', null)->where('owner_id', '<>', '')->get();
        $html = '';
        if (!$unitsinfo->isEmpty()) {
            foreach ($unitsinfo as $unit) {
                $html .= '<option value="'.$unit->id.'" data-select2-id="un-'.$unit->id.'">'.$unit->unit_no.'</option>';
            }
        } else {
            $html .= '<option value="" data-select2-id="un-0" selected>No Units Found</option>';
        }

        return $html;
    }

    public function fetch_owner_tenant(Request $request)
    {
        if ($request->lease_type=='permanent') {
            $owners = Owner::where('type', 'owner')->latest()->get();
        } else {
            $owners = Owner::where('type', 'tenant')->latest()->get();
        }
        
        $html = '';
        if (!$owners->isEmpty()) {
            foreach ($owners as $owner) {
                $html .= '<option value="'.$owner->id.'" data-select2-id="'.$owner->id.'">'.$owner->firstname.' '.$owner->lastname.'</option>';
            }
        } else {
            $html .= '<option value="" data-select2-id="0" selected>No Data Found</option>';
        }

        return $html;
    }

    public function fetch_unit_owner(Request $request)
    {
        $ownerinfo =   Unit::with('owner')->where('id', $request->unitid)->first();
        if ($ownerinfo) {
            $ownername = $ownerinfo->owner->firstname.' '.$ownerinfo->owner->middlename.' '.$ownerinfo->owner->lastname;
        } else {
            $ownername = 'No Owner Assinged to this unit';
        }
        return $ownername;
    }

    public function show($id)
    {
        $authRole = auth()->user()->getRoleNames()[0];

 if ($authRole == 'Superadmin') {
            $notifications = DB::table('notifications')->where('read_at', null)->latest()->get();
        } elseif ($authRole == 'Owner') {
            $notifications = DB::table('notifications')->where('owner_id', auth()->user()->id)->where('status', '!=', 'pending')->where('owner_read_at', '=', null)->latest()->get();
        } elseif ($authRole == 'Tenant') {
            $notifications = DB::table('notifications')->where('owner_id', auth()->user()->id)->where('status', '!=', 'pending')->where('owner_read_at', '=', null)->latest()->get();
        } else {
        }

        $leases = Lease::where('id', $id)->with('documents', 'amenities', 'residents')->latest()->get();
        return view('leases.index', compact('leases', 'notifications'));
    }
}
