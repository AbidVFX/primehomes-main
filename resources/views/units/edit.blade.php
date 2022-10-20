@extends('layouts.app')


@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Update Unit Information</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Units</a></li>
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
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h5><i class="icon fas fa-ban"></i> Alert!</h5>
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
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Update Unit Information</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              
              <form action="{{ route('units.update',$unit->id) }}" method="POST">
              @csrf
              @method('PUT')
                <div class="card-body">
                <div class="row">
                  <div class="col-4">
                    <div class="form-group">
                      <label>Select Building</label>
                      <select class="form-control" name="project_id"  required>
                        @foreach($projects as $project)
                        <option value="{{$project->id}}" @if($project->id==$unit->project_id) Selected @endif>{{$project->name}}(phase {{$project->phase}})</option>
                       @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group" >
                      <label>Select Onwer</label>
                      <select name="owner_id" class="form-control select2" style="width: 100%;" tabindex="-1" aria-hidden="true" id="owner-od">
                        <option value=""  data-select2-id="on-0" selected>Select Owner</option>
                        @foreach($owners as $owner)
                        <option value="{{$owner->id}}" data-select2-id="on-{{$owner->id}}" @if ($unit->owner_id == $owner->id) selected @endif>{{$owner->firstname.' '.$owner->lastname }}</option>
                        @endforeach
                   
                      </select>
                    </div>
                  </div>
                  <div class="col-4">
                  <label for="exampleInputEmail1">Unit No</label>
                    <input type="text" name="unit_no" value="{{$unit->unit_no}}" class="form-control" placeholder="Please Enter Unit No">
                  </div>
                  
                </div>
                <div class="row">
                  <div class="col-4">
                    <label for="exampleInputEmail1">Unit Type</label>
                    <select class="form-control" name="unit_type" required>
                      
                      <option value="STUDIO"  @if($unit->unit_type=='STUDIO') Selected @endif>STUDIO</option>
                      <option value="ONE BED ROOM" @if($unit->unit_type=='ONE BED ROOM') Selected @endif>ONE BED ROOM</option>
                      <option value="STUDIO PREMIERE" @if($unit->unit_type=='STUDIO PREMIERE') Selected @endif>STUDIO PREMIERE</option>
                    
                    </select>
                  </div>
                  <div class="col-4">
                    <label for="exampleInputEmail1">Floor Area</label>
                    <input type="number" step=0.1 name="floor_area" value="{{$unit->floor_area}}"  class="form-control" placeholder="Please Enter Floor Area">
                  </div>
                  <div class="col-4">
                    <label for="exampleInputEmail1">Parking Available</label>
                    <select class="form-control" name="parking" required>
                      <option value="Y"  @if($unit->parking=='Y') Selected @endif>Y</option>
                      <option value="N" @if($unit->parking=='N') Selected @endif>N</option>
                    </select>
                  </div>
                  
                  
                 
                </div>
                <div class="row">
                  <div class="col-4">
                    <label for="exampleInputEmail1">Slot No</label>
                    <input type="number" name="slot_no" value="{{$unit->slot_no}}"  class="form-control" placeholder="Please Enter Slot Number">
                  </div>
                  <div class="col-4">
                    <label for="exampleInputEmail1">Parking Area</label>
                    <input type="number" step=0.1 name="parking_area" value="{{$unit->parking_area}}" class="form-control" placeholder="Please Enter Parking Area">
                  </div>
                  <div class="col-4">
                    <label for="exampleInputEmail1">Parking Location</label>
                    <input type="text" name="parking_location" value="{{$unit->parking_location}}" class="form-control" placeholder="Please Enter Location">
                  </div>
                 
                </div>
                <div class="row">
                  <div class="col-4">
                    <label for="exampleInputEmail1">Unit Fully Paid</label>
                    <select class="form-control" name="unit_paid" required>
                      <option value="Y"  @if($unit->unit_paid=='N') Selected @endif>Y</option>
                      <option value="N"  @if($unit->unit_paid=='N') Selected @endif>N</option>
                    </select>
                  </div>
                  <div class="col-4">
                    <label for="exampleInputEmail1">Water Meter Number</label>
                    <input type="number" name="water_meter_number" value="{{$unit->water_meter_number}}"  class="form-control" placeholder="Please Enter Water Meter Number">
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