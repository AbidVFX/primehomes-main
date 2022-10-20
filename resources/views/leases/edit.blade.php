@extends('layouts.app')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Update Lease</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Leases</a></li>
                        <li class="breadcrumb-item active">Update</li>
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
                            <h3 class="card-title">Update Lease</h3>
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
                        <form action="{{ route('leases.update', $lease->id) }}" enctype="multipart/form-data" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Lease Date:</label>
                                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                                <input type="text" name="lease_date" value="{{ $lease->lease_date }}"
                                                    class="form-control datetimepicker-input"
                                                    data-target="#reservationdate" />
                                                <div class="input-group-append" data-target="#reservationdate"
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
                                                <input type="text" name="lease_end" value="{{ $lease->lease_end }}"
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
                                            <label>Select Building</label>
                                            <select class="form-control" name="project_id" id="building-options" required>
                                                @foreach ($projects as $project)
                                                    <option value="{{ $project->building_id }}"
                                                        @if ($lease->project_id == $project->building_id) selected @endif>
                                                        {{ $project->name }}(phase {{ $project->phase }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>



                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Select Unit</label>
                                            <select name="unit_id" class="form-control select2 select2-hidden-accessible"
                                                style="width: 100%;" id="unit-options" required>
                                                <option value="{{ $lease->unit_id }}">{{ $lease->unit->unit_no }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label for="exampleInputEmail1">Lease Type</label>
                                        <select class="form-control" name="lease_type" id="lease-options" required>
                                            <option value="">Select Lease Type</option>
                                            <option value="short_term_lease"
                                                @if ($lease->lease_type == 'short_term_lease') selected @endif>Short Term Lease</option>
                                            <option value="long_term_lease"
                                                @if ($lease->lease_type == 'long_term_lease') selected @endif>Long Term Lease</option>
                                            <option value="permanent" @if ($lease->lease_type == 'permanent') selected @endif>
                                                Permanent</option>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Select Owner/Tenant</label>
                                            <select name="resident_id"
                                                class="form-control select2 select2-hidden-accessible" style="width: 100%;"
                                                tabindex="-1" aria-hidden="true" name="owner_id" id="owners-options"
                                                required>
                                                <option value="{{ $lease->resident_id }}" selected>
                                                    {{ $lease->owner->firstname }} {{ $lease->owner->lastname }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <label for="exampleInputEmail1">Status Of Account</label>
                                        <select class="form-control" name="status_of_account" required>
                                            <option value="Y" @if ($lease->status_of_account == 'Y') selected @endif>Y
                                            </option>
                                            <option value="N" @if ($lease->status_of_account == 'N') selected @endif>N
                                            </option>

                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Lease Document 1</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="filenames[lease_document1]"
                                                        class="custom-file-input" id="exampleInputFile">
                                                    <label class="custom-file-label" for="exampleInputFile">
                                                        @if ($lease->documents)
                                                            {{ $lease->documents->lease_document1 }}
                                                        @endif
                                                    </label>
                                                </div>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Upload</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Lease Document 2</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="filenames[lease_document2]"
                                                        class="custom-file-input" id="exampleInputFile">
                                                    <label class="custom-file-label" for="exampleInputFile">
                                                        @if ($lease->documents)
                                                            {{ $lease->documents->lease_document2 }}
                                                        @endif
                                                    </label>
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
                                            <label for="exampleInputFile">Lease Document 3</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="filenames[lease_document3]"
                                                        class="custom-file-input" id="exampleInputFile">
                                                    <label class="custom-file-label" for="exampleInputFile">
                                                        @if ($lease->documents)
                                                            {{ $lease->documents->lease_document3 }}
                                                        @endif
                                                    </label>
                                                </div>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Upload</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Lease Document 4</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="filenames[lease_document4]"
                                                        class="custom-file-input" id="exampleInputFile">
                                                    <label class="custom-file-label" for="exampleInputFile">
                                                        @if ($lease->documents)
                                                            {{ $lease->documents->lease_document4 }}
                                                        @endif
                                                    </label>
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
                                            <option value="N" @if ($lease->amenity == 'Y') selected @endif>N
                                            </option>
                                            <option value="Y" @if ($lease->amenity == 'Y') selected @endif>Y
                                            </option>
                                        </select>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-4" id="amenities-options"
                                        @if ($lease->amenity == 'N') style="display:none;" @endif>
                                        <div class="form-group">
                                            <label>Select Amenities</label>
                                            <select class="select2" name="amenities[]" multiple="multiple"
                                                data-placeholder="Select a Amenities" style="width: 100%;">

                                                @forelse($amenities as $amenity)
                                                    <option value="{{ $amenity->id }}"
                                                        @foreach ($lease->amenities as $element) @if ($element->amenity_id == $amenity->id) selected
                                selected @endif
                                                        @endforeach
                                                        >{{ $amenity->name }}</option>
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
                                            <table
                                                class="table tableover table-striped table-bordered table-hover tablefull12">
                                                <thead>
                                                    <tr class="font13">
                                                        <th>Name</th>
                                                        <th>Relation</th>
                                                        <th>Valid Id *Max Size 200KB</th>


                                                    </tr>
                                                </thead>
                                                @if (!$lease->residents->isEmpty())
                                                    @foreach ($lease->residents as $key => $resident)
                                                        <tr id="row{{ $key }}">
                                                            <td>
                                                                <div class="form-group">
                                                                    <input type="text"
                                                                        value="{{ $resident->resident_name }}"
                                                                        name="addmore[{{ $key }}][resident_name]"
                                                                        class="form-control">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group">
                                                                    <input type="text"
                                                                        value="{{ $resident->resident_relation }}"
                                                                        name="addmore[{{ $key }}][resident_relation]"
                                                                        class="form-control">
                                                                </div>
                                                            </td>
                                                            <td>

                                                                <div class="form-group">
                                                                    <div class="input-group">
                                                                        <div class="custom-file">
                                                                            <input type="file"
                                                                                name="addmore[{{ $key }}][resident_information]"
                                                                                class="custom-file-input"
                                                                                id="exampleInputFile">
                                                                            <label class="custom-file-label"
                                                                                for="exampleInputFile">{{ $resident->resident_information }}</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <input type="hidden" name="resident_ids[]"
                                                                value="{{ $resident->id }}">
                                                            <td>
                                                                @if ($key == 0)
                                                                    <button type="button" class='btn btn-primary start'
                                                                        onclick="addMoreField()"><i
                                                                            class='fas fa-plus'></i><span>Add</span></button>
                                                                @else
                                                                    <button type="button" class='btn btn-danger delete'
                                                                        onclick="delete_row('{{ $key }}')"><i
                                                                            class='fas fa-trash'></i><span>Delete</span></button>
                                                                @endif

                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr id="row0">
                                                        <td>
                                                            <div class="form-group">
                                                                <input type="text" placeholder="Enter residant name"
                                                                    name="addmore[0][resident_name]" class="form-control">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <input type="text" placeholder="Enter resident relation"
                                                                    name="addmore[0][resident_relation]" class="form-control">
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
                                                        <input type="hidden" name="resident_ids[]" value="0abc">
                                                        <td><button type="button" class='btn btn-primary start'
                                                                onclick="addMoreField()"><i
                                                                    class='fas fa-plus'></i><span>Add</span></button></td>
                                                    </tr>
                                                @endif
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
                $('#unit-options').select2();
                $('#owners-options').select2();
                $('.select2').select2();
                bsCustomFileInput.init();
                //Initialize Select2 Elements
                $('#unit-options').select2({
                    theme: 'bootstrap4'
                });
                $('#owners-options').select2({
                    theme: 'bootstrap4'
                });
                //Date picker
                $('#reservationdate').datetimepicker({
                    format: 'YYYY-MM-DD'
                });
                $('#building-options').on('change', function() {

                    var project_id = $(this).val();
                    $('#unit-options').select2('destroy');
                    if (project_id) {

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
                    $('#owners-options').select2('destroy');
                    if (lease_type) {
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

            function addMoreField() {
                var table = document.getElementById("tableID");
                var table_len = (table.rows.length);
                var id = parseInt(table_len - 1);
                var div =
                    "<td><div class='form-group'><input type='text' placeholder='' id='addformemail' value='' name='addmore[" +
                    id +
                    "][resident_name]' class='form-control'></div></td><td><div class='form-group'><input type='text' placeholder='' id='addformemail' value='' name='addmore[" +
                    id +
                    "][resident_relation]' class='form-control'></div></td><td><div class='form-group'><div class='input-group'><div class='custom-file'><input type='file' name='addmore[" +
                    id +
                    "][resident_information]' class='custom-file-input' id='exampleInputFile'><label class='custom-file-label' for='exampleInputFile'>Choose file</label></div><div class='input-group-append'><span class='input-group-text'>Upload</span></div></div></div></td><input type='hidden' name='resident_ids[]' value='" +
                    id + "'abc>";

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
