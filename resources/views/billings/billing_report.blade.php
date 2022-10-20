@extends('layouts.app')


@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Invoice Report</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Invoices</a></li>
                        <li class="breadcrumb-item active">Report</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check"></i> Alert!</h5>
            {{ $message }}
        </div>
    @endif
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <form method="GET" action="{{ url('billing/reports') }}">

                                <div class="form-group" style="margin-left: 1rem; margin-top: 1rem; ">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Filter by Month</label>
                                                <select class="form-control" name="month">
                                                    <option value="">Select Month</option>
                                                    @foreach ($month_list as $month)
                                                        <option value="{{ $month->month }}">{{ $month->month }}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Filter by Year</label>
                                                <select class="form-control" name="year">
                                                    <option value="">Select Year</option>
                                                    @foreach ($year_list as $year)
                                                        <option value="{{ $year->year }}">{{ $year->year }}</option>
                                                    @endforeach

                                                </select>
                                            </div>

                                        </div>
                                        <div class="col-md-2"
                                            style="
                                        margin-top: 31px;
                                    ">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary"><i
                                                        class="fa fa-filter"></i></button>
                                            </div>

                                        </div>
                                        <div class="col-md-1"
                                            style="
                                        margin-top: 31px;
                                        margin-left: -124px;
                                    ">
                                            <div class="form-group">
                                                <a href="/billing/reports" class="btn btn-danger"><i class="fa fa-times"
                                                        id="reset"></i></a>
                                            </div>

                                        </div>
                                    </div>

                            </form>

                            <table id="example1" class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Building</th>
                                        <th>Unit</th>
                                        <th>Owner Name</th>
                                        <th>Billing Month</th>
                                        <th>Total Bill</th>
                                        <th>Status</th>
                                        <th>action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @php $i = 0 @endphp
                                    @forelse ($billings as $billing)
                                        @php
                                            $owner = App\Models\Owner::find($billing->unitowner->owner_id);
                                            $total = $rent = $water = $violation = $membership = 0;
                                            
                                            foreach ($billing->billing_detail as $detail) {
                                                switch ($detail->type) {
                                                    case 'default':
                                                        $rent = $detail->price;
                                                        break;
                                                    case 'water':
                                                        $water = $detail->price * $detail->consumption;
                                                        break;
                                                    case 'violation':
                                                        $violation = $detail->price;
                                                        break;
                                            
                                                    case 'membership':
                                                        $membership = $detail->price;
                                                        break;
                                                }
                                            }
                                            $total = $rent + $water + $violation + $membership;
                                        @endphp

                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $billing->building->name }}</td>
                                            <td>
                                                @if ($billing->unitowner)
                                                    {{ $billing->unitowner->unit_no }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($owner)
                                                    {{ $owner->firstname }} {{ $owner->lastname }}
                                                @else
                                                    Owner Not Assigned
                                                @endif
                                            </td>
                                            <td>{{ $billing->month }} {{ $billing->year }}</td>
                                            <td>{{ number_format((float) $total, 2, '.', '') }}</td>
                                            <td>
                                                {{ $billing->status }}
                                            </td>
                                            <td> @can('billing-invoice')
                                                    <a target="_blank" class="btn btn-primary"
                                                        href="{{ URL('billinginvoice', $billing->id) }}">View
                                                        Invoice</a>
                                                @endcan
                                            </td>
                                        </tr>

                                    @empty
                                        <tr>
                                            <td colspan="13" align="center">No Data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>

                </div>

            </div>
        </div>
    </section>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
