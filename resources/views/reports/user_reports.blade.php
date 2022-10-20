@extends('layouts.app')


@section('content')
    <div id="complain-report-page">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Users Listing</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Users</a></li>
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

        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fa fa-check"></i> Alert!</h5>
                {{ $message }}
            </div>
        @endif
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <form id="myForm">
                                    <div class="form-group" style="margin-left: 1rem; margin-top: 1rem; ">
                                        <div class="row">

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Filter by Role Name</label>
                                                    <select class="form-control" name="role_name" id="role_name">
                                                        <option value="">Select Role
                                                        </option>
                                                        @foreach ($user_role_names as $role)
                                                            <option value="{{ $role->type }}" <?php echo $role->type == $selectedRole ? 'selected' : ''; ?>>
                                                                {{ $role->type }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-2" style="margin-top: 31px;">
                                                <div class="form-group">
                                                    <button type="submit" id="submit-btn" class="btn btn-primary"><i
                                                            class="fa fa-filter"></i></button>
                                                    <a class="btn btn-danger" href="javascript:void(0);"
                                                        onclick="window.location.href='/reports/user'"><i
                                                            class="fa fa-times"></i></a>
                                                </div>
                                            </div>
                                        </div>

                                </form>
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>email</th>
                                            <th>Role Name</th>
                                            <th>Pending Complaints</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i = 0 @endphp
                                        @forelse ($users as $user)
                                            @php
                                                $inprogressCompCount = DB::table('complains')
                                                    ->where('employee_id', $user->id)
                                                    ->where('status', 'in_progress')
                                                    ->get();
                                                $completedCompCount = DB::table('complains')
                                                    ->where('employee_id', $user->id)
                                                    ->where('status', 'resolved')
                                                    ->get();
                                            @endphp

                                            <tr>
                                                <td>{{ ++$i }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->type }}</td>
                                                @if ($user->type == 'Electrician' or $user->type == 'Plumber' or $user->type == 'Janitor')
                                                    <td><a class="badge badge-warning">in progress:
                                                            <b>{{ $inprogressCompCount->count() }}</b>
                                                        </a>
                                                        <a class="badge badge-success">Resolved:
                                                            <b>{{ $completedCompCount->count() }}</b>
                                                        </a>
                                                        <a href="javascript:void(0);"
                                                            onclick="viewComplainDetail('{{ $user->id }}')"
                                                            class="">View
                                                            Detail
                                                        </a>

                                                    </td>
                                                @else
                                                    <td></td>
                                                @endif
                                            </tr>

                                        @empty
                                            <tr>
                                                <td colspan="13" align="center">Set your filters to see the data</td>
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
    </div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        // Select 2
        $('#role_name').select2();
        $('#submit-btn').submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: '{{ url('reports/user') }}',
                type: 'get',
                data: $('#myForm')
                    .serialize(), // Remember that you need to have your csrf token included
                dataType: 'json',
                success: function(_response) {

                },
                error: function(_response) {
                    // Handle error
                }
            });
        });
    });
</script>

<script>
    function viewComplainDetail(id) {
        let url = '/reports/complains-detail/' + id
        $.ajax({
            type: 'GET',
            url: url,
            data: {
                res: 1
            },
            success: function(data) {
                $('#complain-report-page').html(data)
            }
        });
    };
</script>
