@extends('layouts.app')


@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add Building</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Building</a></li>
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
                            <h3 class="card-title">Add Building</h3>
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
                        <form action="{{ route('projects.store') }}" enctype="multipart/form-data" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Building ID <code> It must be unique.</code></label>
                                    <input type="text" name="building_id" value="{{ old('building_id') }}"
                                        class="form-control" placeholder="Enter Building Id">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Building Name</label>
                                    <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                                        placeholder="Enter Building Name">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Building Rules</label>
                                    <textarea id="summernote" name="rules" placeholder="Write Building Rules..." style="display: none;" > 
                                    </textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Phase</label>
                                    <input type="text" name="phase" value="{{ old('phase') }}" class="form-control"
                                        placeholder="Enter Phase">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Building Address</label>
                                    <input type="text" name="address" value="{{ old('address') }}" class="form-control"
                                        placeholder="Enter Building Address">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Association Dues <code> This will be multiplied with
                                            unit area and parking(if available).</code></label>
                                    <input type="number" step="any" name="association_dues"
                                        value="{{ old('association_dues') }}" class="form-control" value="0"
                                        placeholder="Enter Building Address">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Due Days <code> These days will be added to billing
                                            issue date</code></label>
                                    <input type="number" name="due_days" value="{{ old('due_days') }}" class="form-control"
                                        placeholder="Enter Billing due days">
                                </div>

                                
                                <div class="form-group">
                                    <label for="exampleInputFile">Image (optional)</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="image" class="custom-file-input"
                                                id="exampleInputFile">
                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Upload</span>
                                        </div>
                                    </div>
                                </div>


                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </section>

@endsection
