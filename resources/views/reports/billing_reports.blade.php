@extends('layouts.app')


@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Billing Listing</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Billing</a></li>
                        <li class="breadcrumb-item active">Listing</li>
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

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fa fa-check"></i> Alert!</h5>
            {{ $message }}
        </div>
    @endif
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <form id="myForm">
                                <div class="form-group" style="margin-left: 1rem; margin-top: 1rem; ">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Filter by Building</label>
                                                <select class="form-control" name="project_id" id="building-options">
                                                    <option value="">Select Building</option>
                                                    @foreach ($buildings_list as $building)
                                                        <option value="{{ $building->id }}"
                                                            {{ $building->id == $building_id ? 'selected' : '' }}>
                                                            {{ $building->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        @php
                                            $unit_selected = DB::table('units')
                                                ->where('id', $unit_id)
                                                ->first();
                                            $all_unit_building = DB::table('units')
                                                ->where('project_id', $building_id)
                                                ->get();
                                        @endphp

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Filter by Unit</label>
                                                <select name="unit_id"
                                                    class="form-control select2 select2-hidden-accessible" id="unit-options"
                                                    style="width: 100%" onchange="(this.value)">

                                                    @if ($unit_id !== null)
                                                        <option value="">Select Unit</option>

                                                        @foreach ($all_unit_building as $building)
                                                            <option value="{{ $building->id }}"
                                                                {{ $building->unit_no == $unit_selected->unit_no ? 'selected' : '' }}>
                                                                {{ $building->unit_no ?? '' }}</option>
                                                        @endforeach
                                                    @else
                                                    @endif

                                                </select>

                                            </div>
                                        </div>


                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Filter by Status</label>
                                                <select class="form-control" name="status" id="status-options">
                                                    <option value="">Select Status
                                                    </option>
                                                    <option value="pending" {{ $status_id == 'pending' ? 'selected' : '' }}>
                                                        Pending
                                                    </option>
                                                    <option value="paid" {{ $status_id == 'paid' ? 'selected' : '' }}>
                                                        Paid
                                                    </option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Filter by Month</label>
                                                <select class="form-control" name="month" id="month-options">
                                                    <option value="">Select Month
                                                    </option>
                                                    <option value="January" {{ $month_id == 'January' ? 'selected' : '' }}>
                                                        January
                                                    </option>
                                                    <option value="February"
                                                        {{ $month_id == 'February' ? 'selected' : '' }}>
                                                        February
                                                    </option>
                                                    <option value="March" {{ $month_id == 'March' ? 'selected' : '' }}>
                                                        March
                                                    </option>
                                                    <option value="April" {{ $month_id == 'April' ? 'selected' : '' }}>
                                                        April
                                                    </option>
                                                    <option value="May" {{ $month_id == 'May' ? 'selected' : '' }}>
                                                        May
                                                    </option>
                                                    <option value="June" {{ $month_id == 'June' ? 'selected' : '' }}>
                                                        June
                                                    </option>
                                                    <option value="July" {{ $month_id == 'July' ? 'selected' : '' }}>
                                                        July
                                                    </option>
                                                    <option value="August" {{ $month_id == 'August' ? 'selected' : '' }}>
                                                        August
                                                    </option>
                                                    <option value="September"
                                                        {{ $month_id == 'September' ? 'selected' : '' }}>
                                                        September
                                                    </option>
                                                    <option value="October" {{ $month_id == 'October' ? 'selected' : '' }}>
                                                        October
                                                    </option>
                                                    <option value="November"
                                                        {{ $month_id == 'November ' ? 'selected' : '' }}>
                                                        November
                                                    </option>
                                                    <option value="December"
                                                        {{ $month_id == 'December ' ? 'selected' : '' }}>
                                                        December
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Filter by Year</label>
                                                <select class="form-control" name="year_id" id="year-options">
                                                    <option value="">Select Year</option>
                                                    @foreach ($year_list as $year)
                                                        <option value="{{ $year->all_years }}"
                                                            {{ $year->all_years == $year_id ? 'selected' : '' }}>
                                                            {{ $year->all_years }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2" style="margin-top: 31px;">
                                            <div class="form-group">
                                                <button type="submit" id="submit-btn" class="btn btn-primary"><i
                                                        class="fa fa-filter"></i></button>
                                                <a class="btn btn-danger" href="javascript:void(0);"
                                                    onclick="window.location.href='/reports/billing'"><i
                                                        class="fa fa-times"></i></a>
                                            </div>
                                        </div>
                                    </div>

                            </form>
                            <table id="example2" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Building</th>
                                        <th>Unit</th>
                                        <th>Owner Name</th>
                                        <th>Billing Month</th>
                                        <th>Total Bill</th>
                                        <th>Status</th>
                                        <th>Action</th>
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
                                                <div>
                                                    <select class="form-control" disabled
                                                        @if ($billing->status == 'paid') disabled @endif>
                                                        <option value="paid"
                                                            @if ($billing->status == 'paid') selected @endif>Paid</option>
                                                        <option value="pending"
                                                            @if ($billing->status == 'pending') selected @endif>Pending
                                                        </option>
                                                    </select>

                                                </div>
                                            </td>
                                            <td>
                                                @if ($owner)
                                                    @can('billing-invoice')
                                                        <a target="_blank" class="btn btn-primary"
                                                            href="{{ URL('billinginvoice', $billing->id) }}">View
                                                            Invoice</a>
                                                    @endcan
                                                @endif
                                            </td>
                                        </tr>

                                    @empty
                                        <tr>
                                            <td colspan="13" align="center">Set your filters to see the data</td>
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
<script>
    $(document).ready(function() {
        $('#submit-btn').submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: '{{ url('reports/billing') }}',
                type: 'get',
                data: $('#myForm')
                    .serialize(), // Remember that you need to have your csrf token included
                dataType: 'json',
                success: function(_response) {

                },
                error: function(_response) {
                    // Handle error
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {

        $('#unit-options').select2({
            placeholder: "Select Unit",
        });
        $('#owners-options').select2();
        $('#status-options').select2();
        $('#building-options').select2();
        $('#month-options').select2();
        $('#year-options').select2();



        $('#building-options').on('change', function() {
            var project_id = $(this).val();

            if (project_id) {
                $('#unit-options').select2('destroy');
                $.ajax({

                    type: "GET",

                    url: "{{ url('fetchallunits') }}",

                    data: {
                        project_id
                    },

                    success: function(res) {
                        $('#unit-options').select2({
                            allowClear: true,
                            text: "Select Unit",
                            placeholder: "Select Unit",
                        });
                        $("#unit-options").html(res);

                    }

                });

            }

        });
    });
</script>
