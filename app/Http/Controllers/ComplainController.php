<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Unit;
use App\Models\User;
use App\Models\Complain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Notifications\NewUserRegisterNotification;

class ComplainController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:complain-list|complain-create|complain-edit|complain-delete', ['only' => ['index','show']]);
        $this->middleware('permission:complain-create', ['only' => ['create','store']]);
        $this->middleware('permission:complain-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:complain-delete', ['only' => ['destroy']]);
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
            $complaints = Complain::orderBy('id', 'DESC')->get();
        } elseif ($authRole == 'Owner') {
            $complaints = Complain::where('owner_id', auth()->user()->id)->orderBy('id', 'DESC')->get();
        } elseif ($authRole == 'Tenant') {
            $complaints = Complain::where('owner_id', auth()->user()->id)->orderBy('id', 'DESC')->get();
        } else {
            $complaints = Complain::where('employee_id', auth()->user()->id)->orderBy('id', 'DESC')->get();
        }

        if ($authRole == 'Superadmin') {
            $notifications = DB::table('notifications')->where('read_at', null)->latest()->get();
        } elseif ($authRole == 'Owner') {
            $notifications = DB::table('notifications')->where('owner_id', auth()->user()->id)->where('status', '!=', 'pending')->where('owner_read_at', '=', null)->latest()->get();
        } elseif ($authRole == 'Tenant') {
            $notifications = DB::table('notifications')->where('owner_id', auth()->user()->id)->where('status', '!=', 'pending')->where('owner_read_at', '=', null)->latest()->get();
        } else {
            $notifications = 123;
        }
        $employees_list = User::where('type', 'Plumber')->orWhere('type', 'Janitor')->orWhere('type', 'Electrician')->get();
        return view('complains.index', compact('complaints', 'employees_list', 'notifications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $auth_tenant_id = DB::table('users')->where('email', '=', auth()->user()->email)
        ->join('owners', 'owners.primary_email', '=', 'users.email')
        ->join('leases', 'leases.resident_id', '=', 'owners.id')
        ->select('leases.unit_id')
        ->first();

        $auth_owner_id = DB::table('users')->where('email', '=', auth()->user()->email)
        ->join('owners', 'owners.primary_email', '=', 'users.email')
        ->select('owners.id')
        ->first();
        $authRole = auth()->user()->getRoleNames()[0];

        if ($authRole == 'Superadmin') {
            $notifications = DB::table('notifications')->where('read_at', null)->latest()->get();
        } elseif ($authRole == 'Owner') {
            $notifications = DB::table('notifications')->where('owner_id', auth()->user()->id)->where('status', '!=', 'pending')->where('owner_read_at', '=', null)->latest()->get();
        } elseif ($authRole == 'Tenant') {
            $notifications = DB::table('notifications')->where('owner_id', auth()->user()->id)->where('status', '!=', 'pending')->where('owner_read_at', '=', null)->latest()->get();
        } else {
        }
        if ($authRole == 'Owner') {
            $units = Unit::where('owner_id', $auth_owner_id->id)->get();
        } elseif ($authRole == 'Tenant') {
            $units = Unit::where('id', $auth_tenant_id->unit_id)->get();
        } else {
        }
        return view('complains.create', compact('units', 'notifications'));
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
            'description' => 'required',
        ]);
        $role_name = auth()->user()->getRoleNames()[0];
        $owner_id = auth()->user()->id;
        $complain = new Complain();
        $complain->title = $request->title;
        $complain->description = $request->description;
        $complain->owner_id = $owner_id;
        $complain->owner_type = $role_name;
        $complain->unit_id = $request->unit_id;
        $complain->available_date = $request->available_date;
        $complain->save();

        // Send Data to Notification Table
        $notification = DB::table('notifications')->insert([
            'owner_id' => $complain->owner_id,
            'title' => $complain->title,
            'complain_id' => $complain->id,
            'description' => $complain->description,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
            'status' => 'pending',
        ]);

        return redirect()->route('complains.index')
                        ->with('success', 'Complain Recorded successfully.');
    }

    public function assign_employee(Request $request)
    {
        $complain = Complain::find($request->complain_id);
        $complain->employee_id = $request->employee_id;
        $complain->status = 'in_progress';
        $complain->save();
        $notification = DB::table('notifications')->where('complain_id', $request->complain_id)->update([
            'updated_at' => \Carbon\Carbon::now(),
            'status' => 'in_progress',
        ]);

        return response()->json(['success'=>'Record is successfully added']);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Complain  $complain
     * @return \Illuminate\Http\Response
     */
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
            $notifications = 123;
        }
        $complain = Complain::where('id', $id)->first();
        $complain_notes = DB::table('complain_notes')->where('complain_id', $id)->orderBy('created_at', 'DESC')->get();
        return view('complains.show', compact('complain', 'complain_notes', 'notifications'));
    }

    public function status(Request $request)
    {
        $complain = Complain::find($request->id);
        $complain->status = $request->status;
        $complain->save();
        return response()->json(['success'=>'Record is successfully added']);
    }

    public function solve_detail(Request $request)
    {
        $complain = Complain::find($request->complain_resolve_id);
        $complain->solution_detail = $request->solution_detail;
        $complain->status = 'resolved';
        $complain->save();

        $notification = DB::table('notifications')->where('complain_id', $request->complain_resolve_id)->update([
            'updated_at' => \Carbon\Carbon::now(),
            'status' => 'resolved',
        ]);
        return response()->json(['success'=>'Record is successfully added']);
    }

    public function markAsReadAll(Request $request)
    {
        $notification = DB::table('notifications')->update([
            'read_at' => \Carbon\Carbon::now(),
        ]);
        return response()->json(['success'=>'Marked as Read']);
    }

    public function markAsReadAllOwner(Request $request)
    {
        $notification = DB::table('notifications')->where('owner_id', auth()->user()->id)->update([
            'owner_read_at' => \Carbon\Carbon::now(),
        ]);
        return response()->json(['success'=>'Marked as Read']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Complain  $complain
     * @return \Illuminate\Http\Response
     */
    public function edit(Complain $complain)
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
        $units = Unit::where('owner_id', auth()->user()->id)->get();
        return view('complains.edit', compact('complain', 'units', 'notifications'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Complain  $complain
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Complain $complain)
    {
        $complain =  Complain::find($complain->id);
        $complain->title = $request->title;
        $complain->description = $request->description;
        $complain->unit_id = $request->unit_id;
        $complain->save();
        return redirect()->route('complains.index')
        ->with('success', 'Complain Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Complain  $complain
     * @return \Illuminate\Http\Response
     */
    public function destroy(Complain $complain)
    {
        $complain->delete();
        return back()->with('success', 'Deleted Successfully!');
    }

    public function add_notes(Request $request)
    {
        request()->validate([
            'note_document' => 'mimes:png,jpeg,jpg',
            'note' => 'required',
        ]);
        if ($image = $request->file('note_document')) {
            $destinationPath = public_path('uploads/');
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
        } else {
            $profileImage = "null";
        }
        DB::table('complain_notes')->Insert([
    'complain_id' => $request->complain_id,
    'note' => $request->note,
    'note_document' => "$profileImage",
    'created_by' => auth()->user()->id,
    'created_at' => \Carbon\Carbon::now(),
    'updated_at' => \Carbon\Carbon::now(),
    ]);

        return back()->with('success', 'Your Note Successfully added!');
    }
}
