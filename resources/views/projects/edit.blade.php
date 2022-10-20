@extends('layouts.app')


@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Update Building</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Building</a></li>
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
                            <h3 class="card-title">Update Building</h3>
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
                        <form action="{{ route('projects.update', $project->id) }}" enctype="multipart/form-data"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Building ID <code> It must be unique.</code></label>
                                        <input type="text" name="building_id" class="form-control"
                                            value="{{ old('building_id', $project->building_id) }}"
                                            placeholder="Enter Building Id">
                                    </div>
                                    <label for="exampleInputEmail1">Building Name</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $project->name) }}" placeholder="Enter Building Name">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Building Rules</label>
                                    <textarea id="summernote" name="rules" placeholder="Write Building Rules..." style="display: none;" > {{ $project->rules }}
                                    </textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Phase</label>
                                    <input type="text" name="phase" class="form-control"
                                        value="{{ old('phase', $project->phase) }}" placeholder="Enter Phase">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Building Address</label>
                                    <input type="text" name="address" class="form-control"
                                        value="{{ old('address', $project->address) }}" placeholder="Enter Building Address">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Association Dues <code> This will be multiplied with
                                            unit area and parking(if available).</code></label>
                                    <input type="number" step="any" name="association_dues" class="form-control"
                                        value="{{ old('association_dues', $project->association_dues) }}"
                                        placeholder="Enter Assocation Dues">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Due Days <code> These days will be added to billing
                                            issue date</code></label>
                                    <input type="number" name="due_days" value="{{ old('due_days', $project->due_days) }}"
                                        class="form-control" placeholder="Enter Billing due days">
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
