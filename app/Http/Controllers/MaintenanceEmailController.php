<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceEmail;
use Illuminate\Http\Request;

class MaintenanceEmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mailbody = MaintenanceEmail::find(1)->select('mail_body')->first();
        return view('maintenance_service.maintenance_mail',compact('mailbody'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $maintenanceEmail = MaintenanceEmail::find(1);
        $maintenanceEmail->mail_body = $request->mail_body;
        $maintenanceEmail->save();
        return back()->with('success','template updated successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MaintenanceEmail  $maintenanceEmail
     * @return \Illuminate\Http\Response
     */
    public function show(MaintenanceEmail $maintenanceEmail)
    {
        $data = MaintenanceEmail::find(1)->first();
        $DateOfMaintenance = "26/05/22";
        $ReportType = "SimpleReport";
        return view('mail.maintenance_service',compact('data','ReportType','DateOfMaintenance'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MaintenanceEmail  $maintenanceEmail
     * @return \Illuminate\Http\Response
     */
    public function edit(MaintenanceEmail $maintenanceEmail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MaintenanceEmail  $maintenanceEmail
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
       
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MaintenanceEmail  $maintenanceEmail
     * @return \Illuminate\Http\Response
     */
    public function destroy(MaintenanceEmail $maintenanceEmail)
    {
        //
    }
}
