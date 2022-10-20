@extends('layouts.app')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Maintenance</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Maintenance</a></li>
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
                            <h3 class="card-title">Create Maintenance</h3>
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
                        <form action="{{ route('MaintenanceService.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Report Type</label>
                                        <input type="text" name="ReportType"  class="form-control" placeholder="Report Type" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Date Of Maintenance</label>
                                        <input type="date" name="DateOfMaintenance" value="{{ old('DateOfMaintenance') }}"
                                            class="form-control" placeholder="Date Of Maintenance" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Purpose Of Maintenance</label>
                                        <textarea name="PurposeOfMaintenance" value="{{ old('PurposeOfMaintenance') }}"
                                            class="form-control" placeholder="Purpose Of Maintenance"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Timeframe</label>
                                        <input type="text" name="Timeframe" value="{{ old('Timeframe') }}"
                                            class="form-control" placeholder="Timeframe">
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">email</label>
                                        <input type="text" name="email" value="{{ old('email') }}"
                                            class="form-control" placeholder="Please Enter Email">
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

<script>
    function showhide(val) {
        (val == 'N') ? $('.authorized-person').hide(): $('.authorized-person').show();
    }
    $(document).ready(function() {
        bsCustomFileInput.init();
    });
</script>
