@extends('layouts.app')


@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Building Listing</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Building</a></li>
                        <li class="breadcrumb-item active">Listing</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check"></i> Alert!</h5>
            {{ $message }}
        </div>
    @endif
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Building Name</th>
                                        <th>Buidling Id</th>
                                        <th>Phase</th>
                                        <th>Address</th>
                                        <th>Assocation Dues</th>
                                        <th>Due Days</th>
                                        <th>Image</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($buildings as $building)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $building->name }}</td>
                                            <td>{{ $building->building_id }}</td>
                                            <td>{{ $building->phase }}</td>
                                            <td>{{ $building->address }}</td>
                                            <td>{{ number_format((float) $building->association_dues, 2, '.', '') }}</td>
                                            <td>{{ $building->due_days }}</td>
                                            <td><img src="{{ asset('/buildings/thumbnail/' . $building->image) }}"
                                                    alt="image" /></td>
                                            <td>
                                                <form action="{{ route('projects.destroy', $building->id) }}"
                                                    method="POST">
                                                    @can('project-edit')
                                                        <a class="btn btn-primary"
                                                            href="{{ route('projects.edit', $building->id) }}">Edit</a>
                                                    @endcan
                                                    @csrf
                                                    @method('DELETE')
                                                    @can('project-delete')
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    @endcan

                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="13" align="center">No Data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>

                </div>

            </div>
        </div>
    </section>
@endsection
