@extends('layouts.app')


@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Update Amenity</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Amenities</a></li>
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
                            <h3 class="card-title">Update Amenity</h3>
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
                        <form action="{{ route('amenities.update', $amenity->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Amenity Name</label>
                                    <input type="text" name="name" value="{{ $amenity->name }}" class="form-control"
                                        placeholder="Enter Amenity Name">
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Amenity Dues <code> Put 0 for free</code></label>
                                    <input type="number" step=0.1 name="charges" class="form-control"
                                        value="{{ $amenity->charges }}" placeholder="Enter Amenity Address">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Cycle For Charges</label>
                                    <select class="form-control" name="charges_cycle" required>
                                        <option value="monthly" @if ($amenity->charges_cycle == 'monthly') selected @endif>Monthly
                                        </option>
                                        <option value="yearly" @if ($amenity->charges_cycle == 'yearly') selected @endif>Yearly
                                        </option>

                                    </select>
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
