<?php
    
namespace App\Http\Controllers;
    
use File;
use Response;
use App\Models\Unit;
use App\Models\Owner;
use App\Models\Project;
use App\Exports\UnitExport;
use App\Imports\UnitImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class UnitController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:unit-list|unit-create|unit-edit|unit-delete', ['only' => ['index','show']]);
        $this->middleware('permission:unit-create', ['only' => ['create','store']]);
        $this->middleware('permission:unit-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:unit-delete', ['only' => ['destroy']]);
        $this->middleware('permission:unit-import', ['only' => ['unit_import_view','unit_import']]);
        $this->middleware('permission:unit-download', ['only' => ['downloadfile']]);
        $this->middleware('permission:unit-export', ['only' => ['unit_export']]);


    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //\DB::enableQueryLog();
        //$units = Unit::with('building','owner')->latest();
        $units = Unit::query()

        ->leftjoin('projects as p','p.id', '=', 'units.project_id')
        ->leftjoin('owners as or','or.id', '=', 'units.owner_id')
        ->get([
            'units.*', //to get ids and timestamps
            'or.title',
            'or.firstname',
            'or.lastname',
            'p.building_id',
            'p.name as building_name',
            'p.phase'
        ]);
       $authRole = auth()->user()->getRoleNames()[0];

 if ($authRole == 'Superadmin') {
            $notifications = DB::table('notifications')->where('read_at', null)->latest()->get();
        } elseif ($authRole == 'Owner') {
            $notifications = DB::table('notifications')->where('owner_id', auth()->user()->id)->where('status', '!=', 'pending')->where('owner_read_at', '=', null)->latest()->get();
        } elseif ($authRole == 'Tenant') {
            $notifications = DB::table('notifications')->where('owner_id', auth()->user()->id)->where('status', '!=', 'pending')->where('owner_read_at', '=', null)->latest()->get();
        } else {
        }

        //dd(\DB::getQueryLog());
        return view('units.index',compact('units','notifications'));
    }

      /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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

        $projects = Project::latest()->get();
        $owners = Owner::where('type','owner')->latest()->get();
        return view('units.create',compact('projects','owners','notifications'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        request()->validate([
            'project_id' => 'required',
            'unit_no' => 'required',
            'floor_area'=> 'required',
            'parking' => 'required',
            'slot_no' => 'required_if:parking,Y',
            'parking_area' => 'required_if:parking,Y',
            'parking_location' => 'required_if:parking,Y'

        ]);
        if (Unit::where('unit_no', '=', $request->unit_no)->where('project_id', '=', $request->project_id)->exists()) {
            return redirect()->route('units.create')
            ->with('destroy','Unit No: '.$request->unit_no.' Already Exist In current Building');
         }
        Unit::create($request->all());
    
        return redirect()->route('units.index')
                        ->with('success','Unit created successfully.');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Unit  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Unit $unit)
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

        $owners = Owner::where('type','owner')->latest()->get();
        $projects = Project::latest()->get();
        return view('units.edit',compact('unit','projects','owners','notifications'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Unit  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Unit $unit)
    {
        
        request()->validate([
            'project_id' => 'required',
            'unit_no' => 'required',
            'floor_area'=> 'required',
            'parking' => 'required',
            'slot_no' => 'required_if:parking,Y',
            'parking_area' => 'required_if:parking,Y',
            'parking_location' => 'required_if:parking,Y'

        ]);
        
       
        if (Unit::where('id', '!=', $unit->id)->where('unit_no', '=', $request->unit_no)->where('project_id', '=', $request->project_id)->exists()) {
            return redirect()->back()
            ->with('destroy','Unit No: '.$request->unit_no.' Already Exist In current Building');
         }
        $unit->update($request->all());
    
        return redirect()->route('units.index')
                        ->with('success','Unit updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Unit  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();
    
        return redirect()->route('units.index')
                        ->with('success','Unit deleted successfully');
    }


    public function unit_import_view()
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

       return view('units.import',compact('notifications'));
    }
     
    /**
    * @return \Illuminate\Support\Collection
    */
    public function unit_export() 
    {
        return Excel::download(new UnitExport, 'unit.xlsx');
    }
     
    /**
    * @return \Illuminate\Support\Collection
    */
    public function unit_import() 
    {
        request()->validate([
           
            'file' => 'required',

        ]);
        
        Excel::import(new UnitImport,request()->file('file'));
             
        return redirect()->route('units.index')
                        ->with('success','Data imported Successfully');
    }

    public function downloadfile()
    {
        $filepath = public_path('units.xlsx');
        return Response::download($filepath); 
    }
}