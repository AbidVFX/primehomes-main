@extends('layouts.app')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    @php
    $auth_role = auth()
        ->user()
        ->getRoleNames()[0];
    @endphp

    @if ($auth_role == 'Tenant')
        {{-- Tanent Dashboard --}}
        <div class="content">
            <div class="container-fluid">

                <div class="card card-success">
                    <div class="card-body">
                        <div class="col-lg-12">

                            <!-- /.card -->

                            <div class="card">
                                <div class="card-header border-0">
                                    <h3 class="card-title">My Units</h3>

                                </div>
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-striped table-valign-middle">
                                        <thead>
                                            <tr>
                                                <th>Building</th>
                                                <th>Unit No</th>
                                                <th>Status</th>
                                                <th>Billing Status</th>
                                                <th>Lease Start</th>
                                                <th>Lease End</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- @forelse($myunits as $unit)
                                                <tr>
                                                    <td>
                                                        {{ $unit->name }} ({{ $unit->phase }})
                                                    </td>
                                                    <td>{{ $unit->unit_no }}</td>
                                                    <td>
                                                        {{ $unit->lease_type }}

                                                    </td>
                                                    <td>
                                                        {{ $unit->status_of_account }}
                                                    </td>
                                                    <td>{{ $unit->lease_date }}</td>
                                                    <td>{{ $unit->lease_end }}</td>

                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" align="center">No Data Found</td>
                                                </tr>
                                            @endforelse --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                        <br />
                        <!-- /.col-md-6 -->
                        {{-- @if ($unit->status_of_account == 'Y') --}}
                            <div class="col-lg-12">
                                <!-- /.card -->
                                <div class="card">
                                    <div class="card-header border-0">
                                        <h3 class="card-title">My Recent Bills</h3>
                                    </div>
                                    <div class="card-body table-responsive p-0">
                                        <table class="table table-striped table-valign-middle">
                                            <thead>
                                                <tr>
                                                    <th>Building</th>
                                                    <th>Unit No</th>
                                                    <th>Month/Year</th>
                                                    <th>Total Bill</th>
                                                    <th>Status</th>
                                                    <th>Due Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($mybills as $bills)
                                                    @php
                                                        $total = $rent = $water = $violation = $membership = 0;
                                                        foreach ($bills->billing_detail as $detail) {
                                                            switch ($detail->type) {
                                                                case 'default':
                                                                    $rent = $detail->price;
                                                                    break;
                                                                case 'water':
                                                                    $water = $detail->price * $detail->consumption;
                                                                    break;
                                                                case 'violation':
                                                                    $violation = $detail->price;
                                                                    break;
                                                        
                                                                case 'membership':
                                                                    $membership = $detail->price;
                                                                    break;
                                                            }
                                                        }
                                                        $total = $rent + $water + $violation + $membership;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $bills->building->name }}</td>
                                                        <td>{{ $bills->unitowner->unit_no }}</td>
                                                        <td>{{ $bills->month }}-{{ $bills->year }}</td>
                                                        <td>{{ number_format((float) $total, 2, '.', '') }}</td>
                                                        <td>{{ $bills->status }}</td>
                                                        <td>20-{{ $bills->month }}-{{ $bills->year }}</td>
                                                        <td><a target="_blank"
                                                                href="{{ url('billinginvoice', $bills->id) }}">View
                                                                Invoice</a>
                                                        </td>
                                                    </tr>
                                                {{-- @empty
                                                    <tr>
                                                        <td colspan="4" align="center">No data found</td>
                                                    </tr>
                                                @endforelse --}}

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        {{-- @else
                        @endif --}}

                        <h5 class="mb-2">PrimeHomes Projects</h5>
                        <div class="row">
                            @forelse($project as $building)
                                <div class="col-md-12 col-lg-6 col-xl-4">



                                    <div class="card mb-2 bg-gradient-dark">
                                        <img class="card-img-top" src="{{ asset('buildings/' . $building->image) }}"
                                            alt="Dist Photo 1">
                                        <div class="card-img-overlay d-flex flex-column justify-content-end">
                                            <h5 class="card-title text-primary text-white">{{ $building->name }}
                                                ({{ $building->phase }})
                                            </h5>
                                            <p class="card-text text-white pb-2 pt-1">{{ $building->address }} </p>
                                            <a href="#" class="text-white">Last update 2 mins ago</a>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="card card-danger collapsed-card">
                                            <div class="card-header">
                                                <h3 class="card-title">Building Rules</h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool"
                                                        data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" style="display: none;">
                                                <div>
                                                    {!! html_entity_decode($building->rules) !!}
                                                </div>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                        <!-- /.card -->
                                    </div>
                                </div>
                            @empty
                            @endforelse
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        {{-- End Tanent Dashbaord --}}
    @else
        {{-- Others dashboard --}}
        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">

                <div class="card card-success">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">

                                <!-- /.card -->

                                <div class="card">
                                    <div class="card-header border-0">
                                        <h3 class="card-title">My Units</h3>

                                    </div>
                                    <div class="card-body table-responsive p-0">
                                        <table class="table table-striped table-valign-middle">
                                            <thead>
                                                <tr>
                                                    <th>Building</th>
                                                    <th>Unit No</th>
                                                    <th>Status</th>
                                                    <th>Billing Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($myunits as $unit)
                                                    <tr>
                                                        <td>
                                                            {{ $unit->building->name }} ({{ $unit->building->phase }})
                                                        </td>
                                                        <td>{{ $unit->unit_no }}</td>
                                                        <td>
                                                            <!-- <small class="text-success mr-1">
                                                                                                      <i class="fas fa-arrow-up"></i>
                                                                                                      12%
                                                                                                    </small> -->
                                                            @if ($unit->lease)
                                                                {{ $unit->lease->lease_type }}
                                                            @else
                                                                Vacant
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($unit->lease)
                                                                {{ $unit->lease->status_of_account }}
                                                            @else
                                                                Vacant
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" align="center">No Data Found</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- /.card -->
                            </div>
                            <!-- /.col-md-6 -->
                            <div class="col-lg-6">

                                <!-- /.card -->

                                <div class="card">
                                    <div class="card-header border-0">
                                        <h3 class="card-title">My Recent Bills</h3>
                                    </div>
                                    <div class="card-body table-responsive p-0">
                                        <table class="table table-striped table-valign-middle">
                                            <thead>
                                                <tr>
                                                    <th>Building</th>
                                                    <th>Unit No</th>
                                                    <th>Month/Year</th>
                                                    <th>Total Bill</th>
                                                    <th>Status</th>
                                                    <th>Due Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($mybills as $bills)
                                                    @php
                                                        $total = $rent = $water = $violation = $membership = 0;
                                                        foreach ($bills->billing_detail as $detail) {
                                                            switch ($detail->type) {
                                                                case 'default':
                                                                    $rent = $detail->price;
                                                                    break;
                                                                case 'water':
                                                                    $water = $detail->price * $detail->consumption;
                                                                    break;
                                                                case 'violation':
                                                                    $violation = $detail->price;
                                                                    break;
                                                        
                                                                case 'membership':
                                                                    $membership = $detail->price;
                                                                    break;
                                                            }
                                                        }
                                                        $total = $rent + $water + $violation + $membership;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $bills->building->name }}</td>
                                                        <td>{{ $bills->unitowner->unit_no }}</td>
                                                        <td>{{ $bills->month }}-{{ $bills->year }}</td>
                                                        <td>{{ number_format((float) $total, 2, '.', '') }}</td>
                                                        <td>{{ $bills->status }}</td>
                                                        <td>20-{{ $bills->month }}-{{ $bills->year }}</td>
                                                        <td><a target="_blank"
                                                                href="{{ url('billinginvoice', $bills->id) }}">View
                                                                Invoice</a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" align="center">No data found</td>
                                                    </tr>
                                                @endforelse

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h5 class="mb-2">PrimeHomes Projects</h5>
                        <div class="row">
                            @forelse($project as $building)
                                <div class="col-md-12 col-lg-6 col-xl-4">



                                    <div class="card mb-2 bg-gradient-dark">
                                        <img class="card-img-top" src="{{ asset('buildings/' . $building->image) }}"
                                            alt="Dist Photo 1">
                                        <div class="card-img-overlay d-flex flex-column justify-content-end">
                                            <h5 class="card-title text-primary text-white">{{ $building->name }}
                                                ({{ $building->phase }})
                                            </h5>
                                            <p class="card-text text-white pb-2 pt-1">{{ $building->address }} </p>
                                            <a href="#" class="text-white">Last update 2 mins ago</a>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="card card-danger collapsed-card">
                                            <div class="card-header">
                                                <h3 class="card-title">Building Rules</h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool"
                                                        data-card-widget="collapse"><i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" style="display: none;">
                                                <div>
                                                    {!! html_entity_decode($building->rules) !!}
                                                </div>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                        <!-- /.card -->
                                    </div>
                                </div>
                            @empty
                            @endforelse
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
    @endif
@endsection
