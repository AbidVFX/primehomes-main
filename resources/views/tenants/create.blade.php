@extends('layouts.app')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add Tenant</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Tenants</a></li>
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
                            <h3 class="card-title">Add Tenant</h3>
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
                        <form action="{{ route('tenants.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-1">
                                        <label for="exampleInputEmail1">Title</label>
                                        <input type="text" name="title" value="Mr" class="form-control" required>
                                    </div>
                                    <div class="col-3">
                                        <label for="exampleInputEmail1">First Name</label>
                                        <input type="text" name="firstname" value="{{ old('firstname') }}"
                                            class="form-control" placeholder="Please Enter First Name">
                                    </div>
                                    <div class="col-4">
                                        <label for="exampleInputEmail1">Last Name</label>
                                        <input type="text" name="lastname" value="{{ old('lastname') }}"
                                            class="form-control" placeholder="Please Enter Last Name">
                                    </div>
                                    <div class="col-4">
                                        <label for="exampleInputEmail1">Middle Name</label>
                                        <input type="text" name="middlename" value="{{ old('middlename') }}"
                                            class="form-control" placeholder="Please Enter Middle Name">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <label for="exampleInputEmail1">Primary Email</label>
                                        <input type="email" name="primary_email" value="{{ old('primary_email') }}"
                                            class="form-control" placeholder="Please Enter Primary Email">
                                    </div>
                                    <div class="col-4">
                                        <label for="exampleInputEmail1">Secondary Email</label>
                                        <input type="email" name="secondary_email" value="{{ old('secondary_email') }}"
                                            class="form-control" placeholder="Please Enter Secondary Email">
                                    </div>
                                    <div class="col-4">
                                        <label for="exampleInputEmail1">Alternate Email</label>
                                        <input type="email" name="alternate_email" value="{{ old('alternate_email') }}"
                                            class="form-control" placeholder="Please Enter Alternate Email">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <label for="exampleInputEmail1">Landline</label>
                                        <input type="text" name="landline" value="{{ old('landline') }}"
                                            class="form-control" placeholder="Please Enter Landline">
                                    </div>
                                    <div class="col-4">
                                        <label for="exampleInputEmail1">Primary Mobile</label>
                                        <input type="text" name="primary_mobile" value="{{ old('primary_mobile') }}"
                                            class="form-control" placeholder="Please Enter Primary Mobile">
                                    </div>
                                    <div class="col-4">
                                        <label for="exampleInputEmail1">Secondary Mobile</label>
                                        <input type="text" name="secondary_mobile" value="{{ old('secondary_mobile') }}"
                                            class="form-control" placeholder="Please Enter Secondary Mobile">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Valid Id</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="valid_id" class="custom-file-input"
                                                        id="exampleInputFile">
                                                    <label class="custom-file-label" for="exampleInputFile">Choose
                                                        file</label>
                                                </div>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Upload</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Other Document</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="other_document" class="custom-file-input"
                                                        id="exampleInputFile">
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
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Emergency Contact Person</label>
                                            <input type="text" name="contact_person"
                                                value="{{ old('contact_person') }}" class="form-control"
                                                id="exampleInputEmail1" placeholder="Enter Emergency contact person name">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Emergency Contact</label>
                                            <input type="text" name="contact_number"
                                                value="{{ old('contact_number') }}" class="form-control"
                                                id="exampleInputEmail1" placeholder="Enter Emergency Cotnact Number">
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
    <script>
        $(document).ready(function() {
            bsCustomFileInput.init();
        });
    </script>
@endsection
