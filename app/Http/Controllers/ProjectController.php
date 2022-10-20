<?php
    
namespace App\Http\Controllers;
    
use Image;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:project-list|project-create|project-edit|project-delete', ['only' => ['index']]);
        $this->middleware('permission:project-create', ['only' => ['create','store']]);
        $this->middleware('permission:project-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:project-delete', ['only' => ['destroy']]);
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

        $buildings = Project::latest()->paginate(5);
        return view('projects.index',compact('buildings','notifications'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
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

        return view('projects.create',compact('notifications'));
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
            'building_id' => 'required|unique:projects,building_id',
            'name' => 'required',
            'address' => 'required',
            'phase'=> 'required',
            'association_dues' => 'required'

        ]);
        
        $data = $request->all();
        if($request->hasFile('image')){
            $image = $request->file('image');
            $input['imagename'] = time().'.'.$image->extension();
         
            $destinationPath = public_path('buildings/thumbnail');
            $img = Image::make($image->path());
            $result = $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$input['imagename']);
           
       
            $destinationPath = public_path('buildings');
            $image->move($destinationPath, $input['imagename']);
            $data['image'] = $input['imagename'];

         }
        
        Project::create($data);
    
        return redirect()->route('projects.index')
                        ->with('success','Building created successfully.');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
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

        return view('projects.edit',compact('project','notifications'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        request()->validate([
            'building_id' => 'required|unique:projects,building_id,'.$project->id,
            'name' => 'required',
            'address' => 'required',
            'phase' => 'required',
            'association_dues' => 'required'

        ]);
        
        $data = $request->all();
        if($request->hasFile('image')){
            $image = $request->file('image');
            $input['imagename'] = time().'.'.$image->extension();
         
            $destinationPath = public_path('buildings/thumbnail');
            $img = Image::make($image->path());
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$input['imagename']);
           
       
            $destinationPath = public_path('buildings');
            $image->move($destinationPath, $input['imagename']);
            $data['image'] = $input['imagename'];

        }
        
        $project->update($data);
    
        return redirect()->route('projects.index')
                        ->with('success','Building updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->delete();
    
        return redirect()->route('projects.index')
                        ->with('success','Building deleted successfully');
    }

}