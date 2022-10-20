@extends('layouts.app')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add Lease</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Leases</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Add Lease</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if ($message = Session::get('destroy'))
                            <div class="alert alert-danger">
                                <p>{{ $message }}</p>
                            </div>
                        @endif
                        <form action="{{ route('leases.store') }}" enctype="multipart/form-data" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Lease Start Date *</label>
                                            <div class="input-group date" id="leasestart" data-target-input="nearest">
                                                <input type="text" name="lease_date" value="{{ old('lease_date') }}"
                                                    class="form-control datetimepicker-input" data-target="#leasestart" />
                                                <div class="input-group-append" data-target="#leasestart"
                                                    data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Lease End Date *</label>
                                            <div class="input-group date" id="leaseend" data-target-input="nearest">
                                                <input type="text" name="lease_end" value="{{ old('lease_end') }}"
                                                    class="form-control datetimepicker-input" data-target="#leaseend" />
                                                <div class="input-group-append" data-target="#leaseend"
                                                    data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Select Building *</label>
                                            <select class="form-control" name="project_id" id="building-options" required>
                                                <option value="" selected>Select Building</option>
                                                @foreach ($projects as $project)
                                                    <option value="{{ $project->id }}">{{ $project->name }}(phase
                                                        {{ $project->phase }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Select Unit * <code>Units having owners will be listed
                                                    here</code></label>
                                            <select name="unit_id" class="form-control select2 select2-hidden-accessible"
                                                style="width: 100%;" id="unit-options" onchange="fetchownerinfo(this.value)"
                                                required>

                                            </select>
                                            <span style="color:green; font-weight: bold;" id="owner-information"></span>
                                        </div>

                                    </div>
                                    <div class="col-4">
                                        <label for="exampleInputEmail1">Lease Type *</label>
                                        <select class="form-control" name="lease_type" id="lease-options" required>
                                            <option value="">Select Lease Type</option>
                                            <option value="short_term_lease">Short Term Lease</option>
                                            <option value="long_term_lease">Long Term Lease</option>
                                            <option value="permanent">Permanent</option>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Select Tenant *</label>
                                            <select name="resident_id"
                                                class="form-control select2 select2-hidden-accessible" style="width: 100%;"
                                                tabindex="-1" aria-hidden="true" name="owner_id" id="owners-options"
                                                required>

                                            </select>
                                        </div>
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <label for="exampleInputEmail1">Status Of Account *</label>
                                        <select class="form-control" name="status_of_account" required>
                                            <option value="Y">Y</option>
                                            <option value="N">N</option>

                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Copy Of Notarized Lease Contract *</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="filenames[lease_document1]"
                                                        class="custom-file-input" id="exampleInputFile">
                                                    <label class="custom-file-label" for="exampleInputFile">Choose
                                                        file</label>
                                                </div>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Upload</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Valid Identity *</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="filenames[lease_document2]"
                                                        class="custom-file-input" id="exampleInputFile">
                                                    <label class="custom-file-label" for="exampleInputFile">Choose
                                                        file</label>
                                                </div>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Upload</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Other Document</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="filenames[lease_document3]"
                                                        class="custom-file-input" id="exampleInputFile">
                                                    <label class="custom-file-label" for="exampleInputFile">Choose
                                                        file</label>
                                                </div>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Upload</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Other Document 2</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="filenames[lease_document4]"
                                                        class="custom-file-input" id="exampleInputFile">
                                                    <label class="custom-file-label" for="exampleInputFile">Choose
                                                        file</label>
                                                </div>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Upload</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label for="exampleInputEmail1">Amenities</label>
                                        <select class="form-control" name="amenity" id="amenity-selection" required>
                                            <option value="N">N</option>
                                            <option value="Y">Y</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4" id="amenities-options" style="display:none;">
                                        <div class="form-group">
                                            <label>Select Amenities</label>
                                            <select class="select2" name="amenities[]" multiple="multiple"
                                                data-placeholder="Select a Amenities" style="width: 100%;">
                                                <option value="">Select Amenities</option>
                                                @forelse($amenities as $amenity)
                                                    <option value="{{ $amenity->id }}">{{ $amenity->name }}</option>
                                                @empty
                                                    <option>No Data found</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-secondary">
                                <div class="card-header">
                                    <h3 class="card-title">Residing Persons Detail</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table tableover table-striped table-bordered table-hover tablefull12"
                                            id="tableID">
                                            <thead>
                                                <tr class="font13">
                                                    <th>Name</th>
                                                    <th>Relation</th>
                                                    <th>Valid Id *Max Size 200KB</th>


                                                </tr>
                                            </thead>
                                            <tr id="row0">
                                                <td>
                                                    <div class="form-group">
                                                        <input type="text" placeholder="Enter residant name"
                                                            name="addmore[0][resident_name]"
                                                            value="{{ old('addmore.0.resident_name') }}"
                                                            class="form-control">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="text" placeholder="Relation to listed tenant"
                                                            name="addmore[0][resident_relation]"
                                                            value="{{ old('addmore.0.resident_relation') }}"
                                                            class="form-control">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <div class="custom-file">
                                                                <input type="file"
                                                                    name="addmore[0][resident_information]"
                                                                    class="custom-file-input">
                                                                <label class="custom-file-label"
                                                                    for="exampleInputFile">Choose file</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td><button type="button" class='btn btn-primary start'
                                                        onclick="addMoreField()"><i
                                                            class='fas fa-plus'></i><span>Add</span></button></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </section>
    <script>
        $(document).ready(function() {
            //$('.select2').select2();
            $('#unit-options').select2();
            $('#owners-options').select2();
            //Initialize Select2 Elements

            bsCustomFileInput.init();

            //Date picker
            var date = new Date(); // Now
            date.setDate(date.getDate() + 90);
            $('#leasestart').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#leaseend').datetimepicker({
                format: 'YYYY-MM-DD',
                minDate: moment(date, 'YYYY-MM-DD')
            });
            $('#building-options').on('change', function() {

                var project_id = $(this).val();

                if (project_id) {
                    $('#unit-options').select2('destroy');
                    $.ajax({

                        type: "GET",

                        url: "{{ url('fetchunits') }}",

                        data: {
                            project_id
                        },

                        success: function(res) {
                            $('#unit-options').select2();
                            $("#unit-options").html(res);

                        }

                    });

                }

            });

            $('#lease-options').on('change', function() {
                var lease_type = $(this).val();

                if (lease_type) {
                    $('#owners-options').select2('destroy');
                    $.ajax({
                        type: "GET",
                        url: "{{ url('fetchownertenant') }}",
                        data: {
                            lease_type
                        },
                        success: function(res) {
                            $('#owners-options').select2();
                            $("#owners-options").html(res);
                        }
                    });
                }
            });

            $('#amenity-selection').on('change', function() {
                $(this).val() == 'Y' ? $("#amenities-options").show() : $("#amenities-options").hide()
            });
        });

        function fetchownerinfo(unitid) {
            $("#owner-information").html('');
            $.ajax({

                type: "GET",

                url: "{{ url('fetchunitowner') }}",

                data: {
                    unitid
                },

                success: function(res) {
                    $("#owner-information").html(res);

                }

            });
        }

        function addMoreField() {
            var table = document.getElementById("tableID");
            var table_len = (table.rows.length);
            var id = parseInt(table_len - 1);
            var div =
                "<td><div class='form-group'><input type='text' placeholder='Resident Name' id='addformemail' value='' name='addmore[" +
                id +
                "][resident_name]'  class='form-control'></div></td><td><div class='form-group'><input type='text' placeholder='Relation To Listed Tenant' id='addformemail' value='' name='addmore[" +
                id +
                "][resident_relation]' class='form-control'></div></td><td> <div class='form-group'><div class='input-group'><div class='custom-file'><input type='file' name='addmore[" +
                id +
                "][resident_information]' class='custom-file-input'><label class='custom-file-label' for='exampleInputFile'>Choose file</label></div></div></div></td>";

            var row = table.insertRow(table_len).outerHTML = "<tr id='row" + id + "'>" + div +
                "<td><button class='btn btn-danger delete' onclick='delete_row(" + id +
                ")'><i class='fas fa-trash'></i><span>Delete</span></button></td></tr>";
            bsCustomFileInput.init();
        }

        function delete_row(id) {
            var table = document.getElementById("tableID");
            var rowCount = table.rows.length;
            $("#row" + id).remove();
        }
    </script>
@endsection
