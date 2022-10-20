@extends('layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Update Profile</h1>
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
                            <h3 class="card-title">Update Profile</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <div style="padding: 5px;">
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

                            @if (session()->has('message'))
                                <div class="alert alert-success">
                                    {{ session()->get('message') }}
                                </div>
                            @endif


                            @if (session()->has('error'))
                                <div class="alert alert-danger">
                                    {{ session()->get('error') }}
                                </div>
                            @endif


                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible show fade">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert">
                                            <span>×</span>
                                        </button>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        <form method="POST" action="{{ route('profile.update', $user->id) }}">
                            @csrf
                            @method('put')
                            <div class="card-body">

                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" name="name" value="{{ $user->name }}" class="form-control"
                                        autofocus required>
                                </div>

                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" readonly value="{{ $user->email }}" name=""
                                        class="form-control" autofocus>
                                </div>


                                <div class="form-group">
                                    <label>Old Password</label>
                                    <input type="password" name="old_password" placeholder="Old Password"
                                        class="form-control">
                                </div>

                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="newpassword" placeholder="New Password"
                                        class="form-control">
                                </div>


                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input type="password" name="confirm-password" placeholder="Confirm New Password"
                                        class="form-control">
                                </div>

                            </div>
                            <div class="card-footer text-right">
                                <button class="btn btn-success mr-1" type="submit">Update</button>
                            </div>
                        </form>

                        </form>
                    </div>
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </section>
@endsection

<script>
    function showhide(val) {
        (val == 'N') ? $('.authorized-person').hide(): $('.authorized-person').show();
    }
    $(document).ready(function() {
        bsCustomFileInput.init();
    });
</script>
