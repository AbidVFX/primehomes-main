<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\NoticeMail;
use Illuminate\Http\Request;
use App\Mail\MaintenanceMail;
use App\Models\MaintenanceEmail;
use App\Models\MaintenanceService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class MaintenanceServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('maintenance_service.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $maintenance_service = MaintenanceService::find(1);
        $maintenance_service->ReportType = $request->ReportType;
        $maintenance_service->DateOfMaintenance = $request->DateOfMaintenance;
        $maintenance_service->PurposeOfMaintenance	 = $request->PurposeOfMaintenance;
        $maintenance_service->Timeframe = $request->Timeframe;
        $maintenance_service->email = $request->email;
        $maintenance_service->save();
        return back()->with('success','template updated successfully');
    }

    public function sendMail(Request $request)
    {
        $users = User::where('id','!=','1')->get();
        if ($users->count() > 0) {
            foreach($users as $key => $value){
                if (!empty($value->email)) {
                    $details = [
                      'subject' => 'Test From Nicesnippets.com',
                    ];
                    $data = MaintenanceEmail::find(1)->first();

                    Mail::to($value->email)->send(new MaintenanceMail($data));
                }
            }
        }

        return back();
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MaintenanceService  $maintenanceService
     * @return \Illuminate\Http\Response
     */
    public function show(MaintenanceService $maintenanceService)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MaintenanceService  $maintenanceService
     * @return \Illuminate\Http\Response
     */
    public function edit(MaintenanceService $maintenanceService)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MaintenanceService  $maintenanceService
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MaintenanceService $maintenanceService)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MaintenanceService  $maintenanceService
     * @return \Illuminate\Http\Response
     */
    public function destroy(MaintenanceService $maintenanceService)
    {
        //
    }
}
