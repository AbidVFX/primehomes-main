@extends('layouts.app')


@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Invoices Listing</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Invoices</a></li>
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
                            <table id="example1" class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Building</th>
                                        <th>Unit</th>
                                        <th>Owner Name</th>
                                        <th>Billing Month</th>
                                        <th>Total Bill</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @php $i = 0 @endphp
                                    @forelse ($billings as $billing)
                                        @php
                                            $owner = App\Models\Owner::find($billing->unitowner->owner_id);
                                            $total = $rent = $water = $violation = $membership = 0;
                                            
                                            foreach ($billing->billing_detail as $detail) {
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
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $billing->building->name }}</td>
                                            <td>
                                                @if ($billing->unitowner)
                                                    {{ $billing->unitowner->unit_no }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($owner)
                                                    {{ $owner->firstname }} {{ $owner->lastname }}
                                                @else
                                                    Owner Not Assigned
                                                @endif
                                            </td>
                                            <td>{{ $billing->month }} {{ $billing->year }}</td>
                                            <td>{{ number_format((float) $total, 2, '.', '') }}</td>
                                            <td>
                                                <div>
                                                    <select class="form-control"
                                                        onchange="makepayment(this.value, '{{ $billing->id }}')"
                                                        @if ($billing->status == 'paid') disabled @endif>
                                                        <option value="paid"
                                                            @if ($billing->status == 'paid') selected @endif>Paid</option>
                                                        <option value="pending"
                                                            @if ($billing->status == 'pending') selected @endif>Pending
                                                        </option>
                                                    </select>

                                                </div>
                                            </td>
                                            <td>
                                                <form action="{{ route('billings.destroy', $billing->id) }}"
                                                    method="POST">
                                                    @if ($owner)
                                                        @can('billing-invoice')
                                                            <a target="_blank" class="btn btn-primary"
                                                                href="{{ URL('billinginvoice', $billing->id) }}">View
                                                                Invoice</a>
                                                        @endcan
                                                        @if ($billing->status == 'pending')
                                                            <a target="_self" class="btn btn-danger"
                                                                href="{{ URL('sendnotice', $billing->id) }}"><i
                                                                    class="fa fa-envelope-o" aria-hidden="true"></i>
                                                                Send Notice</a>
                                                        @else
                                                        @endif
                                                    @endif

                                                    @csrf
                                                    @method('DELETE')
                                                    @can('billing-delete')
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
<script>
    function makepayment(value, id) {

        $.ajax({
            url: "{{ route('billingstatus') }}",
            type: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                id: id,
                status: value
            },

            success: function(result) {
                window.location.reload();
            }

        });
    }
</script>
