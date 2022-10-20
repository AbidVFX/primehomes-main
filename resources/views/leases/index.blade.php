@extends('layouts.app')


@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Lease Listing</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Leases</a></li>
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
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center">S. No.</th>
                                        <th>Lease Date</th>
                                        <th>Building</th>
                                        <th>Unit</th>
                                        <th>Owner/Tenant</th>
                                        <th>Lease Type</th>
                                        <th>Status Of Account</th>
                                        <th>Amenity</th>
                                        <th>Availed Amenities</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody class="table-body">
                                    @php $i=1; @endphp
                                    @forelse($leases as $lease)
                                        <tr class="cell-1" data-toggle="collapse" data-target="#demo{{ $i }}">
                                            <td class="text-center">{{ $i }}</td>
                                            <td>{{ $lease->lease_date ?? '' }}</td>
                                            <td><span class="badge badge-danger">{{ $lease->building->name ?? '' }}</span>
                                            </td>
                                            <td>{{ $lease->unit->unit_no ?? '' }}</td>
                                            <td>{{ $lease->owner->firstname ?? '' }} {{ $lease->owner->lastname ?? '' }}
                                            </td>
                                            <td>{{ $lease->lease_type ?? '' }}</td>
                                            <td>{{ $lease->status_of_account ?? '' }}</td>
                                            <td>{{ $lease->amenity ?? '' }}</td>
                                            <td>
                                                @if ($lease->amenity == 'Y')
                                                    @foreach ($lease->amenities as $ament)
                                                        @php
                                                            $amenity = App\Models\Amenitie::find($ament->amenity_id);
                                                        @endphp
                                                        <span class="badge badge-info">{{ $amenity->name ?? '' }}</span>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ route('leases.destroy', $lease->id) }}" method="POST">
                                                    @can('lease-edit')
                                                        <a class="btn btn-primary"
                                                            href="{{ route('leases.edit', $lease->id) }}">Edit</a>
                                                    @endcan
                                                    @csrf
                                                    @method('DELETE')
                                                    @can('lease-delete')
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    @endcan

                                                </form>

                                            </td>
                                        </tr>
                                        @if ($lease->documents)
                                            <tr id="demo{{ $i }}" class="collapse cell-1 row-child"
                                                style="background-color:aqua;">
                                                <td class="text-center" colspan="1"><i class="fa fa-angle-up"></i></td>
                                                <td colspan="3"><a
                                                        href="{{ asset('lease_documents/' . $lease->id . '/' . $lease->documents->lease_document1) }}"
                                                        download>{{ $lease->documents->lease_document1 }}</a></td>
                                                <td colspan="2"><a
                                                        href="{{ asset('lease_documents/' . $lease->id . '/' . $lease->documents->lease_document2) }}"
                                                        download>{{ $lease->documents->lease_document2 }}</a></td>
                                                <td colspan="2"><a
                                                        href="{{ asset('lease_documents/' . $lease->id . '/' . $lease->documents->lease_document3) }}"
                                                        download>{{ $lease->documents->lease_document3 }}</a></td>
                                                <td colspan="2"><a
                                                        href="{{ asset('lease_documents/' . $lease->id . '/' . $lease->documents->lease_document4) }}"
                                                        download>{{ $lease->documents->lease_document4 }}</a></td>
                                            </tr>
                                        @endif
                                        @if ($lease->residents)
                                            @foreach ($lease->residents as $resident)
                                                <tr id="demo{{ $i }}" class="collapse cell-1 row-child"
                                                    style="background-color:beige;">
                                                    <td></td>
                                                    <td colspan="3">Name: {{ $resident->resident_name }}</td>
                                                    <td colspan="2">Relation: {{ $resident->resident_relation }}</td>
                                                    <td colspan="4">Information: <a
                                                            href="{{ asset('lease_relation/' . $lease->id . '/' . $resident->resident_information) }}"
                                                            download>{{ $resident->resident_information }}</a></td>

                                                </tr>
                                            @endforeach
                                        @endif
                                        @php ++$i @endphp
                                    @empty
                                        <tr>
                                            <td colspan="7"><b>No data Found</b></td>
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
