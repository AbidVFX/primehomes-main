@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Complaints Listing</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Complaints</a></li>
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
    @php
        $auth_role = auth()
            ->user()
            ->getRoleNames()[0];
    @endphp
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            @php
                                $unit_selected = DB::table('units')
                                    ->where('id', $unitID)
                                    ->first();
                                $all_unit_building = DB::table('units')
                                    ->where('project_id', $buildingID)
                                    ->get();
                            @endphp

                            {{-- <button id="loadbasic">hI</button> --}}

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
                                                            {{ $building->id == $buildingID ? 'selected' : '' }}>
                                                            {{ $building->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Filter by Unit</label>
                                                <select name="unit_id"
                                                    class="form-control select2 select2-hidden-accessible" id="unit-options"
                                                    style="width: 100%" onchange="(this.value)">

                                                    @if ($unitID !== null)
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
                                                    <option value="" style="background-color: #999">Select Status
                                                    </option>
                                                    <option value="pending" {{ $statusID == 'pending' ? 'selected' : '' }}>
                                                        Pending
                                                    </option>
                                                    <option value="in_progress"
                                                        {{ $statusID == 'in_progress' ? 'selected' : '' }}>
                                                        In Progress
                                                    </option>
                                                    <option value="resolved"
                                                        {{ $statusID == 'resolved' ? 'selected' : '' }}>
                                                        Resolved
                                                    </option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2"
                                            style="
                                        margin-top: 31px;">
                                            <div class="form-group">
                                                <button type="submit" id="submitBTN" class="btn btn-primary"><i
                                                        class="fa fa-filter"></i></button>

                                                <a class="btn btn-danger" href="javascript:void(0);"
                                                    onclick="window.location.href='/reports/complain'"><i
                                                        class="fa fa-times"></i></a>
                                            </div>
                                        </div>
                                    </div>

                            </form>
                            <table id="example2" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Name</th>
                                        <th>Unit No</th>
                                        <th>Building Name</th>
                                        <th>Status</th>
                                        @can('complain-delete')
                                            <th>Assigned Employee</th>
                                        @endcan
                                        @if ($auth_role == 'Janitor' || $auth_role == 'Plumber' || $auth_role == 'Electrician')
                                            <th>Action</th>
                                        @else
                                        @endif
                                        @canany('complain-edit', 'complain-delete')
                                            <th>Action</th>
                                        @endcanany
                                    </tr>
                                </thead>
                                <tbody id="tbody">
                                    @php $i=0 @endphp
                                    @php
                                        $auth_role = auth()
                                            ->user()
                                            ->getRoleNames()[0];
                                    @endphp
                                    @forelse ($complaints as $complain)
                                        <tr>
                                            @php
                                                $owner_id = DB::table('users')
                                                    ->where('id', $complain->owner_id)
                                                    ->first();
                                                $user_id = DB::table('users')
                                                    ->where('id', $complain->employee_id)
                                                    ->first();
                                                
                                                $unit_no = DB::table('units')
                                                    ->where('id', $complain->unit_id)
                                                    ->first();
                                                $building_name = DB::table('projects')
                                                    ->where('id', $unit_no->project_id)
                                                    ->first();
                                                
                                                $diffForHumanDate = Carbon\Carbon::parse($complain->created_at)->diffForHumans();
                                            @endphp
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $owner_id->name, 15 }}</td>
                                            <td>{{ $unit_no->unit_no }}</td>
                                            <td>{{ $building_name->name }}&nbsp;&nbsp;<span
                                                    class="badge badge-info">{{ $building_name->building_id }}</span></td>
                                            <td>
                                                @if ($auth_role == 'Superadmin')
                                                    <select class="form-control" disabled>
                                                        <option value="pending"
                                                            {{ $complain->status == 'pending' ? 'selected' : '' }}>
                                                            Pending
                                                        </option>
                                                        <option value="in_progress"
                                                            {{ $complain->status == 'in_progress' ? 'selected' : '' }}>
                                                            In Progress
                                                        </option>
                                                        <option value="resolved"
                                                            {{ $complain->status == 'resolved' ? 'selected' : '' }}>
                                                            Resolved
                                                        </option>

                                                    </select>
                                                @else
                                                    <select class="form-control" disabled>
                                                        <option value="pending"
                                                            {{ $complain->status == 'pending' ? 'selected' : '' }}>
                                                            Pending
                                                        </option>
                                                        <option value="in_progress"
                                                            {{ $complain->status == 'in_progress' ? 'selected' : '' }}>
                                                            In Progress
                                                        </option>
                                                        <option value="resolved"
                                                            {{ $complain->status == 'resolved' ? 'selected' : '' }}>
                                                            Resolved
                                                        </option>

                                                    </select>
                                                @endif
                                            </td>

                                            @if ($auth_role == 'Superadmin')
                                                <td>{{ $user_id->name ?? 'No Assigned Employee' }}
                                                    @if ($complain->employee_id == null)
                                                    @else
                                                        &nbsp;&nbsp;<span
                                                            class="badge badge-success">{{ $user_id->type ?? '' }}</span>
                                                    @endif
                                                </td>
                                            @elseif ($auth_role == 'Janitor' || $auth_role == 'Plumber' || $auth_role == 'Electrician')
                                                <td>
                                                    <a data-id="{{ $complain->id }}" data-title="{{ $complain->title }}"
                                                        data-diffForHumanDate="{{ $diffForHumanDate }}"
                                                        data-description="{{ $complain->description }}"
                                                        data-available_date="{{ $complain->available_date }}"
                                                        data-status="{{ $complain->status }}"
                                                        class="btn btn-primary detail_id" data-toggle="modal"
                                                        data-target=".bd-example-modal-detail">View
                                                        Detail</a>
                                                    <a class="btn btn-info"
                                                        href="{{ route('complains.show', $complain->id) }}"><i
                                                            class="far fa-comments"></i></a>
                                                </td>
                                            @else
                                            @endif
                                            @canany('complain-edit', 'complain-delete')
                                                <td>
                                                    <a data-id="{{ $complain->id }}" data-title="{{ $complain->title }}"
                                                        data-description="{{ $complain->description }}"
                                                        data-status="{{ $complain->status }}"
                                                        data-diffForHumanDate="{{ $diffForHumanDate }}"
                                                        data-available_date="{{ $complain->available_date }}"
                                                        class="btn btn-primary detail_id" data-toggle="modal"
                                                        data-target=".bd-example-modal-detail">View
                                                        Detail</a>

                                                    <a class="btn btn-info"
                                                        href="{{ route('complains.show', $complain->id) }}"><i
                                                            class="far fa-comments"></i></a>
                                                </td>
                                            @endcanany

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

        <div class="modal fade bd-example-modal-lg" id tabindex="-1" role="dialog" aria-labelledby="ShipOwnerModal"
            aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ShipOwnerModal">Assign Employee</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="alert alert-danger error-msg" style="display:none">
                            <p></p>
                        </div>
                        <form id="btn-storeform" method="POST">
                            @csrf
                            <div class="form-group ">
                                <input type="hidden" name="complain_id" id="complain_id" class="complain_id"
                                    value="">
                                <label>Select Employee</label>
                                <select class="form-control" id="employee_id" name="employee_id" required>
                                    @foreach ($employees_list as $employee)
                                        <option value="{{ $employee->id }}"
                                            @if (old('employee_id') == $employee->id) selected @endif>
                                            {{ $employee->name }} :<strong> {{ $employee->type }}</strong></option>
                                    @endforeach
                                </select>
                            </div>
                    </div>
                    <div class="card-footer text-right">
                        <button id="submit-btn" class="btn btn-primary mr-1" type="submit">Submit</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- Resolved Popup Box --}}
        <div class="modal fade bd-example-modal-resolved" id="resolved_popup" tabindex="-1" role="dialog"
            aria-labelledby="ShipOwnerModal" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ShipOwnerModal">How this problem was solved?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="alert alert-danger error-msg" style="display:none">
                            <p></p>
                        </div>
                        <form id="btn-storedetail" method="POST">
                            @csrf
                            <div class="form-group ">
                                <input type="hidden" name="complain_resolve_id" id="complain_resolve_id"
                                    class="complain_resolve_id" value="">
                            </div>

                            <div class="form-group">
                                <textarea name="solution_detail" placeholder="How this problem solved? Write Here..." id="solution_detail"
                                    class="form-control"></textarea>
                            </div>
                    </div>
                    <div class="card-footer text-right">
                        <button id="submit-btn" class="btn btn-primary mr-1" type="submit">Submit</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- End Resolved Popup Box --}}

        {{-- Detail Popup Modal --}}
        <div class="modal fade bd-example-modal-detail" id="modal-lg" tabindex="-1" role="dialog"
            aria-labelledby="Complaint Detail" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="Complaint Detail">Complain Detail</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="card-body table-responsive p-0">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Title </th>
                                        <th>Description </th>
                                        <th>Available Date <span
                                                class="badge badge-info">FROM&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;TO</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <p id="title"></p>
                                        </td>
                                        <td>
                                            <p id="description"></p>
                                        </td>
                                        <td>
                                            <p id="available_date"></p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- End Detail Popup Modal --}}
        </div>
    </section>
    <script>
        $(document).ready(function() {

            $('#submitBTN').submit(function(event) {
                event.preventDefault();
                $.ajax({
                    url: '{{ url('reports/complain') }}',
                    type: 'get',
                    data: $('#myForm')
                        .serialize(), // Remember that you need to have your csrf token included
                    dataType: 'json',
                    success: function(_response) {
                        // Handle your response..
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
    <script>
        $(document).ready(function() {

            $(".detail_id").on("click", function() {
                var id = $(this).attr("data-id");
                var title = $(this).attr("data-title");
                var description = $(this).attr("data-description");
                var status = $(this).attr("data-status");
                var available_date = $(this).attr("data-available_date");
                var diffForHumanDate = $(this).attr("data-diffForHumanDate");


                // var employee_id = $(this).attr("data-employee_id");
                $("#title").html(title);
                $("#description").html(description);
                $("#status").html(status);
                $("#id").html(id);
                $("#available_date").html(available_date);
                $("#diffForHumanDate").html(diffForHumanDate);
            });
        });
    </script>

    <script>
        $(document).ready(function() {

            $(function() {
                // don't cache ajax or content won't be fresh
                $.ajaxSetup({
                    cache: false
                });
                var ajax_load =
                    "<img src='http://automobiles.honda.com/images/current-offers/small-loading.gif' alt='loading...' />";

                // load() functions
                var loadUrl = "/reports/complain";
                $("#loadbasic").click(function() {
                    $("#tbody").html().load(loadUrl);
                });
                // end  
            });
        });
    </script>


@endsection
