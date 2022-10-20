@extends('layouts.app')


@section('content')
<section class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1>Activity Log</h1>
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Activity Log</li>
        </ol>
        </div>
    </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">{{$type}} Activity Log</h3>
              </div>
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Action</th>
                    <th>User</th>
                    <th>Description(s)</th>
                    <th>Timestamp</th>
                    
                  </tr>
                  </thead>
                  <tbody>
                @forelse($activities as $activity)
                @php
                $user = App\Models\User::find($activity->causer_id);
                @endphp
                  <tr>
                    <td>{{$activity->description}}</td>
                    <td>{{$user->name}} {{$user->type}}</td>
                    <td>{{$activity->properties}}</td>
                    <td>{{$activity->created_at}}</td>
                   
                  </tr>
                  @empty
                  <tr>
                    <td align="center" colspan="5"><b>No logs recorded yet</b></td>
                    
                  </tr>
                 @endforelse
                  </tbody>
                
                </table>
              </div>
              <!-- /.card-header -->
            
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>

@endsection