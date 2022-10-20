@extends('layouts.app')


@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Building Listing</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Building</a></li>
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
                                                <label>Filter Building </label>
                                                <select class="form-control" name="building_id">
                                                    <option value="">Select Building
                                                    </option>
                                                    @foreach ($building_names as $building)
                                                        <option value="{{ $building->id }}" <?php echo $building->id == $selectedBuilding ? 'selected' : ''; ?>>
                                                            {{ $building->name }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2" style="margin-top: 31px;">
                                            <div class="form-group">
                                                <button type="submit" id="submit-btn" class="btn btn-primary"><i
                                                        class="fa fa-filter"></i></button>
                                                <a class="btn btn-danger" href="javascript:void(0);"
                                                    onclick="window.location.href='/reports/user'"><i
                                                        class="fa fa-times"></i></a>
                                            </div>
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
                                    </tr>
                                </thead>
                                <tbody>

                                    @php $i = 0 @endphp
                                    @forelse ($buildings as $building)
                                        @php
                                            $building_name = DB::table('projects')
                                                ->where('id', $building->project_id)
                                                ->first();
                                            $owner_name = DB::table('owners')
                                                ->where('id', $building->owner_id)
                                                ->first();
                                            
                                        @endphp
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $building_name->name }}</td>
                                            <td>
                                                {{ $building->unit_no }}
                                            </td>
                                            <td>
                                                <a href="/owners/{{ $building->owner_id }}" target="_blank">
                                                    {{ $owner_name->firstname }}
                                                    {{ $owner_name->lastname }} </a>
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
                url: '{{ url('reports/building') }}',
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
