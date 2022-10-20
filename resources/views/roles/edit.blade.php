@extends('layouts.app')


@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Update Role</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Roles</a></li>
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
                            <h3 class="card-title">Update Role</h3>
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
                        {!! Form::model($role, ['method' => 'PATCH', 'route' => ['roles.update', $role->id]]) !!}
                        @if ($role->name == 'Superadmin')
                        <div class="card-body">
                            <div class="form-group">
                                <label for="3232">Name</label>
                                {!! Form::text('3223', null, ['placeholder' => 'Name', 'class' => 'form-control', 'readonly' => 'true']) !!}
                            </div>
                            <div class=" form-group">
                                <label>Permission</label>
                                <div class="row">
                                    @foreach ($permission as $value)
                                        <div class="col-md-3 state p-primary-o">
                                            <label>{{ Form::checkbox( $value->id, in_array($value->id, $rolePermissions) ? true : false, ['class' => 'name',]) }}
                                                {{ $value->name }}</label>
                                        </div>
                                        <br />
                                    @endforeach
                                </div>

                            </div>

                            <div class="card-footer">
                                <button type="button" class="btn btn-danger"><i class="fa fa-ban"></i>&nbsp;Restricted</button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                        @else
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Name</label>
                                    {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control']) !!}
                                </div>
                                <div class=" form-group">
                                    <label>Permission</label>
                                    <div class="row">
                                        @foreach ($permission as $value)
                                            <div class="col-md-3 state p-primary-o">
                                                <label>{{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, ['class' => 'name']) }}
                                                    {{ $value->name }}</label>
                                            </div>
                                            <br />
                                        @endforeach
                                    </div>

                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        @endif
                    </div>
                </div>

            </div><!-- /.container-fluid -->
    </section>

@endsection
