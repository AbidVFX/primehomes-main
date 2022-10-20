@extends('layouts.app')


@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Units Listing</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Units</a></li>
                        <li class="breadcrumb-item active">Listing</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            @can('unit-import')
                                <a style="float:right;" class="btn btn-primary" href="{{ URL('unitimportview') }}">Import
                                    Units</a>
                            @endcan
                            @can('unit-export')
                                <a style="float:right;" class="btn btn-secondary mr-2" href="{{ URL('exportunits') }}">Export
                                    Units</a>
                            @endcan

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Building</th>
                                        <th>Leased</th>
                                        <th>Unit No:</th>
                                        <th>Owner</th>
                                        <th>Unit Type</th>
                                        <th>Floor Area</th>
                                        <th>Parking</th>
                                        <th>Slot No:</th>
                                        <th>Area</th>
                                        <th>Unit Fully Paid</th>
                                        <th>Parking Location</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=0; @endphp
                                    @forelse ($units as $unit)
                                        @php
                                            $lease_check = DB::table('leases')
                                                ->where('unit_id', $unit->id)
                                                ->exists();
                                            $lease_name = DB::table('leases')
                                                ->where('unit_id', '=', $unit->id)
                                                ->join('owners', 'owners.id', '=', 'leases.resident_id')
                                                ->select('owners.*', 'owners.*', 'leases.*', 'leases.*')
                                                ->first();
                                        @endphp
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $unit->building_name }} (phase {{ $unit->phase }})</td>
                                            <td>
                                                @if ($lease_check !== false)
                                                    <a href="{{ url('tenants', $lease_name->resident_id) }}"
                                                        target="_blank"> {{ $lease_name->firstname ?? '' }}
                                                        {{ $lease_name->lastname ?? '' }}</a>
                                                    <br />
                                                    <a href="{{ url('leases', $lease_name->id) }}" target="_blank"><i
                                                            class="fa fa-eye"></i>&nbsp;Detail</a>
                                                @else
                                                    Not Leased
                                                @endif
                                            </td>
                                            <td>{{ $unit->unit_no }} </td>
                                            <td>{{ $unit->firstname ?? '' }} {{ $unit->lastname ?? '' }}
                                                <br />
                                                @if ($unit->owner_id != null)
                                                    <a href="{{ url('owners', $unit->owner_id) }}" target="_blank"><i
                                                            class="fa fa-eye"></i>&nbsp;Detail</a>
                                                @else
                                                    No Assigned Owners
                                                @endif
                                            </td>
                                            <td>{{ $unit->unit_type }} </td>
                                            <td>{{ $unit->floor_area }}</td>
                                            <td>{{ $unit->parking }}</td>
                                            <td>{{ $unit->slot_no }}</td>
                                            <td>{{ $unit->parking_area }}</td>
                                            <td>{{ $unit->unit_paid }}</td>
                                            <td>{{ $unit->parking_location }}</td>
                                            <td>
                                                <form action="{{ route('units.destroy', $unit->id) }}" method="POST">

                                                    @can('unit-edit')
                                                        <a class="btn btn-primary"
                                                            href="{{ route('units.edit', $unit->id) }}">Edit</a>
                                                    @endcan
                                                    @csrf
                                                    @method('DELETE')
                                                    @can('unit-delete')
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
