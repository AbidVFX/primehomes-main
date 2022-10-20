<?php
    
namespace App\Http\Controllers;
    
use Image;
use App\Models\Amenitie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AmenitieController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:amenitie-list|amenitie-create|amenitie-edit|amenitie-delete', ['only' => ['index']]);
        $this->middleware('permission:amenitie-create', ['only' => ['create','store']]);
        $this->middleware('permission:amenitie-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:amenitie-delete', ['only' => ['destroy']]);
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

        $amenities = Amenitie::latest()->get();
        return view('amenities.index',compact('amenities','notifications'));
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

        return view('amenities.create','notifications');
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
            'name' => 'required|unique:amenities',
            'charges' => 'required',
        ]);
    
     
        
        Amenitie::create($request->all());
    
        return redirect()->route('amenities.index')
                        ->with('success','Amenity created successfully.');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Amenitie $amenity)
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

        return view('amenities.edit',compact('amenity','notifications'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Amenitie  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Amenitie $amenity)
    {
      
        request()->validate([
            'name' => 'required|unique:amenities,id,'.$amenity->id,
            'charges' => 'required'
        ]);
        
       
        
        $amenity->update($request->all());
    
        return redirect()->route('amenities.index')
                        ->with('success','Amenity updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Amenitie  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Amenitie $amenity)
    {
        $amenity->delete();
    
        return redirect()->route('amenities.index')
                        ->with('success','Amenity deleted successfully');
    }

}