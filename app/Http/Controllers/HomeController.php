<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Unit;
use App\Models\User;
use App\Models\Owner;
use App\Models\Tenant;
use App\Models\Billing;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $project = Project::get();
        if (auth()->user()->type == 'Owner' || auth()->user()->type == 'Tenant') {
            $ownerinfo = Owner::where('primary_email', auth()->user()->email)->first();
            if (auth()->user()->type == 'Tenant') {
                $myunits = Unit::with('lease')->where('owner_id', $ownerinfo->id)->get();
                $myunits = DB::table('leases')
                    ->where('resident_id', '=', $ownerinfo->id)
                    ->join('owners', 'owners.id', '=', 'leases.resident_id')
                    ->join('units', 'leases.unit_id', '=', 'units.id')
                    ->join('projects', 'leases.project_id', '=', 'projects.id')
                    ->select('owners.*', 'owners.*', 'leases.*', 'units.*', 'projects.*')
                    ->get();
            } else {
                $myunits = Unit::with('lease')->where('owner_id', $ownerinfo->id)->get();
            }

            // dd($myunits);
            $unitids = array_column($myunits->toArray(), 'id');

            $mybills = Billing::with('building', 'unitowner')->whereIn('unit_no', $unitids)->latest()->take(10)->get();

            $notifications = DB::table('notifications')->where('owner_id', auth()->user()->id)->where('status', '!=', 'pending')->where('owner_read_at', '=', null)->latest()->get();

            return view('userdashboard', compact('project', 'myunits', 'mybills', 'notifications'));
        }
        $period = now()->subMonths(12)->monthsUntil(now());


        $projectCount = $project->count();
        $owner = Owner::where('type', 'owner')->get();
        $ownerCount = $owner->count();
        $tenant = Owner::where('type', 'tenant')->get();
        $tenantCount = $tenant->count();
        $unit = Unit::get();
        $unitCount = $unit->count();
        $notifications = DB::table('notifications')->where('read_at', null)->latest()->get();

        $users = Owner::selectRaw('YEAR(created_at) as year,COUNT(*) as count')->groupBy('year')->get();

        $data = [
            'year' => json_encode($users->pluck('year')),
            'user' => json_encode($users->pluck('count')),
        ];

        return view('home', compact('projectCount', 'ownerCount', 'unitCount', 'tenantCount', 'notifications','data','period'));
    }

    public function markNotification(Request $request)
    {
        auth()->user()
            ->unreadNotifications
            ->when($request->input('id'), function ($query) use ($request) {
                return $query->where('id', $request->input('id'));
            })
            ->markAsRead();

        return response()->noContent();
    }

}
