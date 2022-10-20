@extends('layouts.app')


@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Import Units</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Units</a></li>
              <li class="breadcrumb-item active">Import</li>
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
                <h3 class="card-title">Import Units</h3>
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
            <div class="card-body">
            <form action="{{ route('unitimport') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="exampleInputBorderWidth2">Please Download Sample File For Column Formatting <code><a href="{{URL('downloadunitsample')}}" target="_blank"><i class="fas fa-info-circle"></i>Download Example Import File </a></code></label>
                <input type="file" name="file" class="form-control">
                <br>
                <button class="btn btn-success">Import units Data</button>
                
            </form>
        </div>
            </div>
          </div>
        </div>
   
      </div><!-- /.container-fluid -->
    </section>

@endsection