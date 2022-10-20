@extends('layouts.app')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Complain</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Complains</a></li>
                        <li class="breadcrumb-item active">Edit</li>
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
                            <h3 class="card-title">Edit Complain</h3>
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


                        <form action="{{ route('complains.update', $complain->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-8">
                                        <label for="title">Title</label>
                                        <input type="text" placeholder="Complain Title" value="{{ $complain->title }}"
                                            name="title" value="{{ old('title') }}" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Select Unit</label>
                                        <select class="form-control" name="unit_id" required>
                                            @foreach ($units as $unit)
                                                <option value="{{ $complain->unit_id }}"
                                                    @if ($complain->unit_id == $complain->unit_id) Selected @endif>{{ $unit->unit_no }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" value="{{ old('description') }}" name="description" rows="3"
                                        placeholder="Please Write Your Complain" style="height: 88px;" required>{{ $complain->description }}</textarea>
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
@endsection

<script>
    function showhide(val) {
        (val == 'N') ? $('.authorized-person').hide(): $('.authorized-person').show();
    }
    $(document).ready(function() {
        bsCustomFileInput.init();
    });
</script>
