<?php

namespace App\Http\Controllers;

use Str;
use Response;
use App\Models\User;
use App\Models\Owner;
use Illuminate\Http\Request;
use App\Exports\TenantExport;
use App\Imports\TenantImport;
use App\Events\UserRegistered;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:tenant-list|tenant-create|tenant-edit|tenant-delete', ['only' => ['index','show']]);
        $this->middleware('permission:tenant-create', ['only' => ['create','store']]);
        $this->middleware('permission:tenant-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:tenant-delete', ['only' => ['destroy']]);
        $this->middleware('permission:tenant-import', ['only' => ['importExportView','import']]);
        $this->middleware('permission:tenant-download', ['only' => ['downloadfile']]);
        $this->middleware('permission:tenant-export', ['only' => ['tenant_export']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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

        $tenants = Owner::where('type', 'tenant')->latest()->get();
        return view('tenants.index', compact('tenants','notifications'));
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

        return view('tenants.create',compact('notifications'));
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
            'title' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'middlename' => 'required',
            'primary_email' => 'required|unique:users,email',
            'secondary_email' => 'required',
            'alternate_email' => 'required',
            'landline' => 'required',
            'primary_mobile' => 'required',
            'secondary_mobile' => 'required',
            'contact_person' => 'required',
            'contact_number' => 'required',

        ]);
        $data = $request->all();
        $data['type'] = 'tenant';
        if ($request->hasFile('valid_id')) {
            $image = $request->file('valid_id');
            $input['imagename'] = Str::slug($image->getClientOriginalName(), '-').'-'.time().'.'.$image->extension();
         
            $destinationPath = public_path('ownersdocument');
            $image->move($destinationPath, $input['imagename']);
            $data['valid_id'] = $input['imagename'];
        }
        if ($request->hasFile('other_document')) {
            $image = $request->file('other_document');
            $input['imagename'] = Str::slug($image->getClientOriginalName(), '-').'-'.time().'.'.$image->extension();
         
            $destinationPath = public_path('ownersdocument');
            $image->move($destinationPath, $input['imagename']);
            $data['other_document'] = $input['imagename'];
        }
        Owner::create($data);
        User::create([
            'name'     => $request->firstname.''.$request->lastname,
            'email'    => $request->primary_email,
            'type'     => 'Owner',
            'password' => Hash::make('123456'),
        ]);
        event(new UserRegistered($request->primary_email));
        return redirect()->route('tenants.index')
                        ->with('success', 'Tenant created successfully.');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Owner  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Owner $tenant)
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

        return view('tenants.edit', compact('tenant','notifications'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Owner  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Owner $tenant)
    {
        request()->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'middlename' => 'required',
            'primary_email' => 'required',
            'secondary_email' => 'required',
            'alternate_email' => 'required',
            'landline' => 'required',
            'primary_mobile' => 'required',
            'secondary_mobile' => 'required',
            'contact_person' => 'required',
            'contact_number' => 'required',

        ]);
        $tenant->update($request->all());
        return redirect()->route('tenants.index')
                        ->with('success', 'Tenant updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Owner  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Owner $tenant)
    {
        $tenant->delete();
        User::where('email', $tenant->primary_email)->delete();
        return redirect()->route('tenants.index')
                        ->with('success', 'Tenant deleted successfully');
    }

    public function tenantdeletebulk(Request $request)
    {
        $ownersemails = Owner::select('primary_email')->whereIn('id', $request->ownerids)->get()->toArray();
        User::whereIn('email', $ownersemails)->delete();
        $result = Owner::whereIn('id', $request->ownerids)->delete();
        return $result;
    }
    public function importExportView()
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

        return view('tenants.import',compact('notifications'));
    }
     
    /**
    * @return \Illuminate\Support\Collection
    */
    public function export()
    {
        return Excel::download(new TenantExport, 'Tenants.xlsx');
    }
     
    /**
    * @return \Illuminate\Support\Collection
    */
    public function import()
    {
        request()->validate([
            'file' => 'required',
        ]);
        
        Excel::import(new TenantImport, request()->file('file'));
        return redirect()->route('tenants.index')->with('success', 'Data imported Successfully');
    }

    public function downloadfile()
    {
        $filepath = public_path('tenantsample.xlsx');
        return Response::download($filepath);
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

        $owners = Owner::where('id', $id)->get();
        return view('tenants.show', compact('owners','notifications'));
    }
}
