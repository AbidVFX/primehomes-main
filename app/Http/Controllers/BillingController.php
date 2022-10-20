<?php

namespace App\Http\Controllers;

use PDF;
use Response;
use App\Models\User;
use App\Models\Owner;
use App\Models\Billing;
use Illuminate\Http\Request;
use App\Models\BillingDetail;
use App\Imports\BillingImport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class BillingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:billing-list|billing-create|billing-edit|billing-delete', ['only' => ['index','show']]);
        $this->middleware('permission:billing-create', ['only' => ['create','store']]);
        $this->middleware('permission:billing-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:billing-delete', ['only' => ['destroy']]);
        // $this->middleware('permission:billing-invoice', ['only' => ['billinginvoice',]]);
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

        $billings = Billing::with('unitowner', 'leaseto', 'billing_detail', 'building')->latest()->get();
        //dd($billings);

        return view('billings.index', compact('billings', 'notifications'));
    }

    public function BillingReport(Request $request)
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

        $data = Billing::with('unitowner', 'leaseto', 'billing_detail', 'building');
        $month_list = DB::table('billings')->select('month')->distinct()->get();
        $year_list = DB::table('billings')->select('year')->distinct()->get();
        $monthID = $yearID = "";
        if ($request->input('month')!="") {
            $monthID = $request->input('month');
            $data->where('month', $request->input('month'));
        }

        if ($request->input('year')!="") {
            $yearID = $request->input('year');
            $data->where('year', $request->input('year'));
        }

        $billings = $data->latest()->get();
        return view('billings.billing_report', compact('billings', 'notifications', 'month_list', 'year_list', 'monthID', 'yearID'));
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

        return view('billings.create', compact('notifications'));
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
           'month' => 'required',
           'year' => 'required',
           'billing_file' => 'required',

        ]);

        $year = $request->input('year');
        $month = $request->input('month');
        $result =  Excel::import(new BillingImport($year, $month), request()->file('billing_file'));

        return redirect()->route('billings.index')
                        ->with('success', 'Excel Imported Successfully.');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Billing  $product
     * @return \Illuminate\Http\Response
     */

    public function billinginvoice($id)
    {
        $billings = Billing::where('id', $id)->with('unitowner', 'building')->first();
        $current_assoication_dues = BillingDetail::where('billing_id', $id)->where('type', 'default')->pluck('price')->first();
        $current_waterbill = BillingDetail::where('billing_id', $id)->where('type', 'water')->first();
        $current_violation = BillingDetail::where('billing_id', $id)->where('type', 'violation')->pluck('price')->first();
        $current_membership = BillingDetail::where('billing_id', $id)->where('type', 'membership')->pluck('price')->first();

        //getting previous water reading
        $previous_waterreading = '';
        $lastmonthid = Billing::select('id')->where('project_id', $billings->project_id)->where('unit_no', $billings->unit_no)->where('id', '<>', $id)->orderBy('month', 'ASC')->first();
        if ($lastmonthid) {
            $previous_waterreading = BillingDetail::where('billing_id', $lastmonthid->id)->where('type', 'water')->pluck('current_reading')->first();
        }

        //getting past unpaid dues
        $pastassociationdues = 0;
        $pastviolationdues = 0;
        $pastwaterdues = 0;
        $pastmembershipdues = 0;
        $periodcovered = '';
        $unpaidduesids = Billing::select('id')->where('project_id', $billings->project_id)->where('unit_no', $billings->unit_no)->where('id', '<>', $id)->where('status', 'pending')->get()->toArray();

        if (count($unpaidduesids)>0) {
            $start = Billing::select('month', 'year')->where('project_id', $billings->project_id)->where('unit_no', $billings->unit_no)->where('id', '<>', $id)->where('status', 'pending')->orderBy('month', 'DESC')->first();
            $end = Billing::select('month', 'year')->where('project_id', $billings->project_id)->where('unit_no', $billings->unit_no)->where('id', '<>', $id)->where('status', 'pending')->orderBy('month', 'ASC')->first();

            $periodcovered = $start->month.' '.$start->year.'-'.$end->month.' '.$end->year;

            $unpaiddues = BillingDetail::whereIn('billing_id', $unpaidduesids)->get();
            $lastmonthreading = BillingDetail::where('billing_id', $end->id)->where('type', 'water')->first();
            $i = 0;
            $len = count($unpaiddues);
            foreach ($unpaiddues as $unpaid) {
                switch ($unpaid->type) {
                    case 'default':
                        $pastassociationdues += $unpaid->price;
                        break;
                    case 'water':
                        $pastwaterdues += $unpaid->price*$unpaid->consumption;
                        break;
                    case 'violation':
                        $pastviolationdues += $unpaid->price;
                        break;

                    case 'membership':
                        $pastmembershipdues += $unpaid->price;
                        break;
                }
                $i++;
            }
        }
        return view('billings.invoice', compact('billings', 'current_assoication_dues', 'current_waterbill', 'current_violation', 'current_membership', 'pastassociationdues', 'pastwaterdues', 'periodcovered', 'previous_waterreading', 'pastviolationdues', 'pastmembershipdues'));
    }

    public function billing_status(Request $request)
    {
        Billing::where('id', $request->id)->update(['status'=>$request->status]);
        return true;
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Billing  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Billing $billing)
    {
        BillingDetail::where('billing_id', $billing->id)->delete();
        $billing->delete();
        return redirect()->route('billings.index')
                        ->with('success', 'Billing record deleted successfully');
    }

    public function sendnotice($id)
    {
        $unit_id = DB::table('billings')->where('id', '=', $id)->first();
        $lease_check = DB::table('leases')
        ->where('unit_id', $unit_id->unit_no)
        ->exists();
        // Mail Invoice Billing PDF
        $billings = Billing::where('id', $id)->with('unitowner', 'building')->first();
        $current_assoication_dues = BillingDetail::where('billing_id', $id)->where('type', 'default')->pluck('price')->first();
        $current_waterbill = BillingDetail::where('billing_id', $id)->where('type', 'water')->first();
        $current_violation = BillingDetail::where('billing_id', $id)->where('type', 'violation')->pluck('price')->first();
        $current_membership = BillingDetail::where('billing_id', $id)->where('type', 'membership')->pluck('price')->first();

        //getting previous water reading
        $previous_waterreading = '';
        $lastmonthid = Billing::select('id')->where('project_id', $billings->project_id)->where('unit_no', $billings->unit_no)->where('id', '<>', $id)->orderBy('month', 'ASC')->first();
        if ($lastmonthid) {
            $previous_waterreading = BillingDetail::where('billing_id', $lastmonthid->id)->where('type', 'water')->pluck('current_reading')->first();
        }

        //getting past unpaid dues
        $pastassociationdues = 0;
        $pastviolationdues = 0;
        $pastwaterdues = 0;
        $pastmembershipdues = 0;
        $periodcovered = '';
        $unpaidduesids = Billing::select('id')->where('project_id', $billings->project_id)->where('unit_no', $billings->unit_no)->where('id', '<>', $id)->where('status', 'pending')->get()->toArray();

        if (count($unpaidduesids)>0) {
            $start = Billing::select('month', 'year')->where('project_id', $billings->project_id)->where('unit_no', $billings->unit_no)->where('id', '<>', $id)->where('status', 'pending')->orderBy('month', 'DESC')->first();
            $end = Billing::select('month', 'year')->where('project_id', $billings->project_id)->where('unit_no', $billings->unit_no)->where('id', '<>', $id)->where('status', 'pending')->orderBy('month', 'ASC')->first();

            $periodcovered = $start->month.' '.$start->year.'-'.$end->month.' '.$end->year;

            $unpaiddues = BillingDetail::whereIn('billing_id', $unpaidduesids)->get();
            $lastmonthreading = BillingDetail::where('billing_id', $end->id)->where('type', 'water')->first();
            $i = 0;
            $len = count($unpaiddues);
            foreach ($unpaiddues as $unpaid) {
                switch ($unpaid->type) {
                    case 'default':
                        $pastassociationdues += $unpaid->price;
                        break;
                    case 'water':
                        $pastwaterdues += $unpaid->price*$unpaid->consumption;
                        break;
                    case 'violation':
                        $pastviolationdues += $unpaid->price;
                        break;

                    case 'membership':
                        $pastmembershipdues += $unpaid->price;
                        break;
                }


                $i++;
            }
        }
        // End Mail Invoice Billing PDF
        $month = $unit_id->month;
        $year = $unit_id->year;
        $pdfName = "Billing-Invoice-".$month."-".$year.".pdf";
        if ($lease_check == true) {
            $tenant_mail = DB::table('leases')
            ->where('unit_id', '=', $unit_id->unit_no)
            ->join('owners', 'owners.id', '=', 'leases.resident_id')
            ->select('owners.*', 'owners.*', 'leases.*', 'leases.*')
            ->first();
            $details = [
                'month' => $unit_id->month,
            ];

            $data["email"] = $tenant_mail->primary_email;
            $data["title"] = "From ItSolutionStuff.com";
            $data["body"] = "This is Demo";

            $pdf = PDF::loadView('billings.invoice', compact('billings', 'current_assoication_dues', 'current_waterbill', 'current_violation', 'current_membership', 'pastassociationdues', 'pastwaterdues', 'periodcovered', 'previous_waterreading', 'pastviolationdues', 'pastmembershipdues'));

            Mail::send('mail.notice', $data, function ($message) use ($data, $pdf, $pdfName) {
                $message->to($data["email"], $data["email"])
                    ->subject($data["title"])
                    ->attachData($pdf->output(), $pdfName);
            });

        // \Mail::to($tenant_mail->primary_email)->send(new \App\Mail\NoticeMail($details));
        } else {
            $owner_mail = DB::table('units')->where('units.id', $unit_id->unit_no)
            ->join('owners', 'owners.id', '=', 'units.owner_id')
            ->select('owners.*', 'units.*')
            ->first();

            $details = [
                'month' => $unit_id->month,
            ];

            $data["email"] = $owner_mail->primary_email;
            $data["title"] = "Due date has been passed!";
            $pdf = PDF::loadView('billings.invoice', compact('billings', 'current_assoication_dues', 'current_waterbill', 'current_violation', 'current_membership', 'pastassociationdues', 'pastwaterdues', 'periodcovered', 'previous_waterreading', 'pastviolationdues', 'pastmembershipdues'));
            Mail::send('mail.notice', $data, function ($message) use ($data, $pdf, $pdfName) {
                $message->to($data["email"], $data["email"])
                        ->subject($data["title"])
                        ->attachData($pdf->output(), $pdfName);
            });
            // \Mail::to($owner_mail->primary_email)->send(new \App\Mail\NoticeMail($details));
            // \Mail::to('abidraheemofficial@gmail.com')->send(new \App\Mail\NoticeMail($details));
        }
        return back()->with('success', 'Notice sent successfully!');
    }
}
