@extends('layouts.app')


@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Owners Listing</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Owners</a></li>
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
        <div class="overlay-wrapper ml-4">
            <div class="overlay" id="loader" style="display:none;"><i class="fas fa-3x fa-sync-alt fa-spin"></i>
                <div class="text-bold pt-2">&nbsp;&nbsp;&nbsp;Sending Email...</div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            @can('owner-import')
                                <a class="btn btn-primary" href="{{ URL('owners_import_view') }}">Import Owners</a>
                            @endcan
                            @can('owner-export')
                                <a class="btn btn-secondary" href="{{ URL('exportowners') }}">Export Owners</a>
                            @endcan
                            @can('owner-delete')
                                <a style="float:right;" class="btn btn-warning" href="javascript:void(0);"
                                    onclick="submitForm()"><i class="fas fa-trash"></i>&nbsp;&nbsp;Delete Bulk Owners</a>

                                <a style="float:right;margin-right: 8px;" class="btn btn-primary " href="javascript:void(0);"
                                    onclick="sendCredentials()"><i class="fas fa-envelope"></i>&nbsp;&nbsp;Send Credentials</a>
                            @endcan

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center pt-3">
                                            <div class="custom-checkbox custom-checkbox-table custom-control">
                                                <input type="checkbox" data-checkboxes="mygroup" data-checkbox-role="dad"
                                                    class="custom-control-input" id="checkbox-all">
                                                <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                            </div>
                                        </th>
                                        <th>Owner Id</th>
                                        <th>First Name</th>
                                        <th>Lastname</th>
                                        <th>Middle Name</th>
                                        <th>Landline</th>
                                        <th>Primary Mobile</th>
                                        <th>Secondary Mobile</th>
                                        <th>Primary Email</th>
                                        <th>Secondary Email</th>
                                        <th>Alternate Email</th>
                                        <th>Emergency Contact Person</th>
                                        <th>Emergency Contact</th>
                                        <th>Valid Id</th>
                                        <th>Other Document</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @php $i=0 @endphp --}}
                                    @forelse ($owners as $owner)
                                        <tr>
                                            <td class="text-center pt-2">
                                                <div class="custom-checkbox custom-control">
                                                    <input type="checkbox" name="owners_ids[]" value="{{ $owner->id }}"
                                                        data-checkboxes="mygroup" class="custom-control-input checkArray"
                                                        id="checkbox-{{ $owner->id }}">
                                                    <label for="checkbox-{{ $owner->id }}"
                                                        class="custom-control-label">&nbsp;</label>
                                                </div>
                                            </td>
                                            <td>{{ $owner->id }}</td>
                                            <td>{{ $owner->firstname }}</td>
                                            <td>{{ $owner->lastname }}</td>
                                            <td>{{ $owner->middlename }}</td>
                                            <td>{{ $owner->landline }}</td>
                                            <td>{{ $owner->primary_mobile }}</td>
                                            <td>{{ $owner->secondary_mobile }}</td>
                                            <td>{{ $owner->primary_email }}</td>
                                            <td>{{ $owner->secondary_email }}</td>
                                            <td>{{ $owner->alternate_email }}</td>
                                            <td>{{ $owner->contact_person }}</td>
                                            <td>{{ $owner->contact_number }}</td>
                                            @can('owner-download')
                                                <td>
                                                    @if ($owner->valid_id)
                                                        <a href="{{ asset('ownersdocument/' . $owner->valid_id) }}" download><i
                                                                class="fas fa-file"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($owner->other_document)
                                                        <a href="{{ asset('ownersdocument/' . $owner->other_document) }}"
                                                            download><i class="fas fa-file"></i>
                                                    @endif
                                                </td>
                                            @endcan


                                            <td>
                                                <form action="{{ route('owners.destroy', $owner->id) }}" method="POST">
                                                    @can('owner-edit')
                                                        <a class="btn btn-primary"
                                                            href="{{ route('owners.edit', $owner->id) }}">Edit</a>
                                                    @endcan
                                                    @csrf
                                                    @method('DELETE')
                                                    @can('owner-delete')
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
    <script>
        $("#checkbox-all").click(function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

        function submitForm() {
            var checkedNum = $('input[name="owners_ids[]"]:checked').length;
            if (!checkedNum) {
                alert('Please check at least one');
            } else {
                var val = [];
                $('input[type="checkbox"]:checked').each(function(i) {
                    val[i] = $(this).val();
                });

                $.ajax({
                    url: '/ownerdeletebulk',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "ownerids": val,
                    },
                    success: function(data) {
                        window.location.reload(true);
                    }
                });
            }
        }
    </script>

    <script>
        $("#checkbox-all").click(function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

        function sendCredentials() {
            var checkedNum = $('input[name="owners_ids[]"]:checked').length;
            if (!checkedNum) {
                alert('Please check at least one');
            } else {
                var val = [];
                $('input[type="checkbox"]:checked').each(function(i) {
                    val[i] = $(this).val();
                });
                $("#loader").show();
                $.ajax({
                    url: '/sendownercredentials',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "ownerids": val,
                    },
                    success: function(data) {
                        $("#loader").hide();
                    }
                });
            }
        }
    </script>
@endsection
