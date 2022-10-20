<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\Complain;
use App\Models\Project;
use App\Models\Reports;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function complain_report(Request $request)
    {
        $complaints = Complain::orderBy('id', 'DESC')->get();
        $employees_list = User::where('type', 'Plumber')->orWhere('type', 'Janitor')->orWhere('type', 'Electrician')->get();

        $authRole = auth()->user()->getRoleNames()[0];

        if ($authRole == 'Superadmin') {
            $notifications = DB::table('notifications')->where('read_at', null)->latest()->get();
        } elseif ($authRole == 'Owner') {
            $notifications = DB::table('notifications')->where('owner_id', auth()->user()->id)->where('status', '!=', 'pending')->where('owner_read_at', '=', null)->latest()->get();
        } elseif ($authRole == 'Tenant') {
            $notifications = DB::table('notifications')->where('owner_id', auth()->user()->id)->where('status', '!=', 'pending')->where('owner_read_at', '=', null)->latest()->get();
        } else {
        }

        $status_list = DB::table('complains')->select('status')->distinct()->get();
        $unit_list = DB::table('complains')
            ->join('units', 'units.id', '=', 'complains.unit_id')
            ->select('units.unit_no')->distinct()
            ->get();

        $buildings_list = DB::table('projects')->get();
        $buildingID = $request->input('project_id');

        $buildingID = $request->input('project_id');
        $statusID = $request->input('status');
        $unitID = $request->input('unit_id');

        $statusID = $unitID = $buildingID = "";
        if ($request->input('status') && $request->input('project_id') && $request->input('unit_id')) {
            $unit_no = DB::table('units')->where('id', $request->input('unit_id'))->first();
            $data_project = DB::table('units')->where('project_id', $request->input('project_id'))
                ->join('complains', 'complains.unit_id', '=', 'units.id')
                ->select('complains.*');
            $data = $data_project->where('unit_id', $unit_no->id)->where('status', $request->input('status'))->get();
        } elseif ($request->input('project_id') && $request->input('unit_id')) {
            $unit_no = DB::table('units')->where('id', $request->input('unit_id'))->first();
            $data_project = DB::table('units')->where('project_id', $request->input('project_id'))
                ->join('complains', 'complains.unit_id', '=', 'units.id')
                ->select('complains.*');
            $data = $data_project->where('unit_id', $unit_no->id)->get();
        } elseif ($request->input('status') && $request->input('project_id')) {
            $data_project = DB::table('units')->where('project_id', $request->input('project_id'))
                ->join('complains', 'complains.unit_id', '=', 'units.id')
                ->select('complains.*');
            $data = $data_project->where('status', $request->input('status'))->get();
        } elseif ($request->input('status') != "") {
            $data = Complain::where('status', $request->input('status'))->orderBy('id', 'DESC')->get();
        } elseif ($request->input('unit_id') != "") {
            $unit_no = DB::table('units')->where('id', $request->input('unit_id'))->first();
            $data = Complain::where('unit_id', $unit_no->id)->get();
        } elseif ($request->input('project_id')) {
            $data = DB::table('units')->where('project_id', $request->input('project_id'))
                ->join('complains', 'complains.unit_id', '=', 'units.id')
                ->select('complains.*')
                ->get();
            // $data = Complain::where('unit_id', $unit_no->id)->get();
        } else {
            $data = Complain::where('status', $request->input('status'))->orderBy('id', 'DESC')->get();
        }
        $complaints = $data;
        // return response()->json(['reports.complain_reports' => 'statusID','unitID','complaints', 'employees_list', 'status_list', 'unit_list','buildings_list','buildingID']);
        return view('reports.complain_reports', compact('statusID', 'unitID', 'complaints', 'employees_list', 'status_list', 'unit_list', 'buildings_list', 'buildingID'));
    }

    public function fetch_units(Request $request)
    {
        $unitsinfo = Unit::where('project_id', $request->project_id)->where('owner_id', '<>', null)->where('owner_id', '<>', '')->get();
        $html = '';
        if (!$unitsinfo->isEmpty()) {
            $html .= '<option value="" >Select Unit</option>';
            foreach ($unitsinfo as $unit) {
                $html .= '<option value="' . $unit->id . '" data-select2-id="un-' . $unit->id . '">' . $unit->unit_no . '</option>';
            }
        }
        return $html;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function billing_report(Request $request)
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
        $year_list = DB::table('general_settings')->select('all_years')->get();
        $buildings_list = DB::table('projects')->get();
        $building_id = $request->input('project_id');
        $unit_id = $request->input('unit_id');
        $status_id = $request->input('status');
        $month_id = $request->input('month');
        $year_id = $request->input('year_id');

        $data = Billing::with('unitowner', 'leaseto', 'billing_detail', 'building')->latest()->get();
        if ($request->input('status') && $request->input('project_id') && $request->input('unit_id') && $request->input('month') && $request->input('year_id')) {
            $data = Billing::with('unitowner', 'leaseto', 'billing_detail', 'building')->where('status', $request->input('status'))->where('unit_no', $request->input('unit_id'))->where('project_id', $request->input('project_id'))->where('month', $request->input('month'))->where('year', $request->input('year_id'))->orderBy('id', 'DESC')->get();
        } elseif ($request->input('project_id') && $request->input('unit_id') && $request->input('month') && $request->input('year_id')) {
            $data = Billing::with('unitowner', 'leaseto', 'billing_detail', 'building')->where('unit_no', $request->input('unit_id'))->where('project_id', $request->input('project_id'))->where('month', $request->input('month'))->where('year', $request->input('year_id'))->orderBy('id', 'DESC')->get();
        } elseif ($request->input('status') && $request->input('project_id') && $request->input('unit_id')) {
            $data = Billing::with('unitowner', 'leaseto', 'billing_detail', 'building')->where('status', $request->input('status'))->where('unit_no', $request->input('unit_id'))->where('project_id', $request->input('project_id'))->orderBy('id', 'DESC')->get();
        } elseif ($request->input('project_id') && $request->input('unit_id')) {
            $data = Billing::with('unitowner', 'leaseto', 'billing_detail', 'building')->where('unit_no', $request->input('unit_id'))->where('project_id', $request->input('project_id'))->orderBy('id', 'DESC')->get();
        } elseif ($request->input('status') && $request->input('project_id')) {
            $data = Billing::with('unitowner', 'leaseto', 'billing_detail', 'building')->where('status', $request->input('status'))->where('project_id', $request->input('project_id'))->orderBy('id', 'DESC')->get();
        } elseif ($request->input('month') && $request->input('year_id')) {
            $data = Billing::with('unitowner', 'leaseto', 'billing_detail', 'building')->where('month', $request->input('month'))->where('year', $request->input('year_id'))->orderBy('id', 'DESC')->get();
        } elseif ($request->input('month')) {
            $data = Billing::with('unitowner', 'leaseto', 'billing_detail', 'building')->where('month', $request->input('month'))->orderBy('id', 'DESC')->get();
        } elseif ($request->input('year_id')) {
            $data = Billing::with('unitowner', 'leaseto', 'billing_detail', 'building')->where('year', $request->input('year_id'))->orderBy('id', 'DESC')->get();
        } elseif ($request->input('project_id')) {
            $data = Billing::with('unitowner', 'leaseto', 'billing_detail', 'building')->where('project_id', $request->input('project_id'))->orderBy('id', 'DESC')->get();
        } elseif ($request->input('unit_id')) {
            $data = Billing::with('unitowner', 'leaseto', 'billing_detail', 'building')->where('unit_no', $request->input('unit_id'))->orderBy('id', 'DESC')->get();
        } elseif ($request->input('status')) {
            $data = Billing::with('unitowner', 'leaseto', 'billing_detail', 'building')->where('status', $request->input('status'))->orderBy('id', 'DESC')->get();
        } else {
            $data = Billing::with('unitowner', 'leaseto', 'billing_detail', 'building')->where('status', $request->input('status'))->orderBy('id', 'DESC')->get();
        }
        $billings = $data;

        // Save Years to Database
        $current_date = Carbon::now();
        $sub_date = Carbon::parse($current_date)->subYear(1);
        $years_added[] = $sub_date;
        $years_added[] = $current_date;

        for ($i = 0; $i < 3; $i++) {
            $current_date = Carbon::parse($current_date)->addYears(1);
            $years_added[] = $current_date;
        }
        foreach ($years_added as $years) {
            if (DB::table('general_settings')->where('all_years', '=', $years->format("Y"))->count() == 0) {
                DB::table('general_settings')->insert([
                    'all_years' => $years->format("Y"),
                ]);
            }
        }
        // End saving years
        return view('reports.billing_reports', compact('billings', 'notifications', 'buildings_list', 'year_list', 'building_id', 'unit_id', 'status_id', 'month_id', 'year_id'));
    }

    public function user_report(Request $request)
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
        // Users Filter
        $selectedRole = "";
        if ($request->input('role_name')) {
            $selectedRole = $request->input('role_name');

            $data = User::where('type', $request->input('role_name'))->orderBy('id', 'DESC')->get();
        } else {
            $data = User::where('type', '!=', 'Superadmin')->where('type', '!=', 'Owner')->where('type', '!=', 'Tenant')->orderBy('id', 'DESC')->get();
        }

        $user_role_names = User::where('type', '!=', 'Superadmin')->where('type', '!=', 'Owner')->where('type', '!=', 'Tenant')->select('type')->distinct()->get();
        $users = $data;
        // return response()->json(['reports.complain_reports' => 'statusID','unitID','complaints', 'employees_list', 'status_list', 'unit_list','buildings_list','buildingID']);
        return view('reports.user_reports', compact('users', 'user_role_names', 'selectedRole'));
    }

    public function ComplainsDetail($user_id)
    {
        $complaints = Complain::where('employee_id', $user_id)->get();
        $employees_list = User::where('type', 'Plumber')->orWhere('type', 'Janitor')->orWhere('type', 'Electrician')->get();
        return view('reports.complains_detail', compact('complaints', 'employees_list'));
    }

    public function building_report(Request $request)
    {
        $building_names = Project::all();

        $selectedBuilding = "";
        if ($request->input('building_id')) {
            $selectedBuilding = $request->input('building_id');
            $data = Project::where('building_id', $request->input('building_id'))->orderBy('id', 'DESC')->get();
        } else {
            $data = Unit::all();
        }

        $buildings = $data;
        return view('reports.building_reports', compact('buildings', 'building_names', 'selectedBuilding'));
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reports  $reports
     * @return \Illuminate\Http\Response
     */
    public function show(Reports $reports)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reports  $reports
     * @return \Illuminate\Http\Response
     */
    public function edit(Reports $reports)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reports  $reports
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reports $reports)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reports  $reports
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reports $reports)
    {
        //
    }
}
