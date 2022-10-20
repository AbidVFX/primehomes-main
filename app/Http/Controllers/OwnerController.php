<?php

namespace App\Http\Controllers;

use Str;
use Response;
use App\Models\User;
use App\Models\Owner;
use App\Exports\OnwerExport;
use App\Imports\OwnerImport;
use Illuminate\Http\Request;
use App\Events\UserRegistered;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class OwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:owner-list|owner-create|owner-edit|owner-delete', ['only' => ['index']]);
        $this->middleware('permission:owner-create', ['only' => ['create','store']]);
        $this->middleware('permission:owner-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:owner-delete', ['only' => ['destroy']]);
        $this->middleware('permission:owner-import', ['only' => ['import']]);
        $this->middleware('permission:owner-export', ['only' => ['importExportView','export']]);
        $this->middleware('permission:owner-download', ['only' => ['download']]);
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

        $owners = Owner::where('type', 'owner')->latest()->get();
        return view('owners.index', compact('owners', 'notifications'));
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

        return view('owners.create', compact('notifications'));
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
            'primary_email' => 'required|unique:users,email',
            'primary_mobile' => 'required',
            'representative' => 'required',
            'contact_person' => 'required_if:representative,Y',
            'contact_number' => 'required_if:representative,Y',
            'valid_id' => 'sometimes|mimes:jpeg,jpg,png,docx|max:4000',
            'other_document' => 'sometimes|mimes:jpeg,jpg,png,docx|max:4000',

        ]);

        $data = $request->all();

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
        // dd($data); exit;
        Owner::create($data);
        // User::create([
        //     'name'     => $request->firstname.''.$request->lastname,
        //     'email'    => $request->primary_email,
        //     'type'     => 'Owner',
        //     'password' => Hash::make('123456'),
        // ]);
        $user = new User();
        $user->name =  $request->firstname.''.$request->lastname;
        $user->email =  $request->primary_email;
        $user->type =  'Owner';
        $user->password =  Hash::make('123456');
        $user->assignRole('Owner');
        $user->save();
        // event(new UserRegistered($request->primary_email));
        return redirect()->route('owners.index')
                        ->with('success', 'Owner created successfully.');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Owner  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Owner $owner)
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

        return view('owners.edit', compact('owner', 'notifications'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Owner  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Owner $owner)
    {
        $userid = User::where('email', $owner->primary_email)->pluck('id')->first();
        request()->validate([
            'title' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'primary_email' => 'required|unique:users,email,'.$userid,
            'primary_mobile' => 'required',
            'representative' => 'required',
            'contact_person' => 'required_if:representative,Y',
            'contact_number' => 'required_if:representative,Y',
            'valid_id' => 'sometimes|mimes:jpeg,jpg,png,docx|max:4000',
            'other_document' => 'sometimes|mimes:jpeg,jpg,png,docx|max:4000',

        ]);
        //gypakycuza@mailinator.com
        $data = $request->all();

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
        $owner->update($data);

        return redirect()->route('owners.index')
                        ->with('success', 'Owner updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Owner  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Owner $owner)
    {
        User::where('email', $owner->primary_email)->delete();
        $unit_exists = DB::table('units')->where('owner_id', $owner->id)->exists();
        $leases_exists = DB::table('leases')->where('resident_id', $owner->id)->exists();
        if ($unit_exists == true) {
            return back()
    ->with('error', 'Owner cant be delete because this owner exists in units.');
        } elseif ($leases_exists == true) {
            return back()
                ->with('error', 'Owner cant be delete because this owner exists in leases.');
        } else {
            $owner->delete();
            return redirect()->route('owners.index')
                ->with('success', 'Owner deleted successfully');
        }
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

        return view('owners.import', compact('notifications'));
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function export()
    {
        return Excel::download(new OnwerExport(), 'Owners.xlsx');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function import()
    {
        request()->validate([

            'file' => 'required',

        ]);
        $import = new OwnerImport();
        $import->import(request()->file('file'));
        //Excel::import(new OwnerImport,request()->file('file'));
        // try {
        //     Excel::import(new OwnerImport,request()->file('file'));
        // } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
        //      $failures = $e->failures();

        //      foreach ($failures as $failure) {
        //         dd($failure->attribute());
        //         // $failure->row(); // row that went wrong
        //          //$failure->attribute(); // either heading key (if using heading row concern) or column index
        //          $errors[] = $failure->row().' '.$failure->errors(); // Actual error messages from Laravel validator
        //          //$failure->values(); // The values of the row that has failed.
        //      }
        //      dd($errors);
        //    return back()->with('validation_errors', $errors);
        // }
        return redirect()->route('owners.index')
                        ->with('success', 'Data imported Successfully');
    }

    public function ownerdeletebulk(Request $request)
    {
        $ownersemails = Owner::select('primary_email')->whereIn('id', $request->ownerids)->get()->toArray();
        User::whereIn('email', $ownersemails)->delete();
        $result = Owner::whereIn('id', $request->ownerids)->delete();
        return $result;
    }

    public function sendownercredentials(Request $request)
    {
        $ownersemails = Owner::select('primary_email')->whereIn('id', $request->ownerids)->get();
       foreach($ownersemails as $email){
       $result =  event(new UserRegistered($email->primary_email));
       }
        return $result;
    }
    public function downloadfile()
    {
        $filepath = public_path('sample.xlsx');
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
        return view('owners.show', compact('owners', 'notifications'));
    }
}
