@extends('layouts.app')


@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Maintenance Email Template</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Maintenance Email Template</a></li>
                        <li class="breadcrumb-item active">Create</li>
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
                            <h3 class="card-title">Update Template</h3>
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
                        <div class="col-md-12 success-mail p-0" style="display: none;">
                            <div class="alert alert-success">
                              Sent Mail Successfully.
                            </div>
                        </div>
                        <form method="POST" action="{{ route('maintenanceMail.store') }}">
                            @csrf
                           
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Mail Body</label>
                                    <textarea id="summernote" name="mail_body" placeholder="Write Mail Body Here..." style="display: none;">{{$mailbody->mail_body}}</textarea>
                                    </textarea>
                                </div>
                                <?php

                                ?>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a href="{{ route('mail.maintenance') }}" class="btn btn-success" target="_blank" >Send Mail to All users</a>
                                </div>

                        </form>
                    </div>
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </section>

@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
{{-- <script>
$(document).ready(function () {


$('.send-mail').click(function (e) {
    e.preventDefault();
        $.ajax({
            url: '{{ route('mail.maintenance') }}',
            type: 'get',
            data: {
                _token:$('meta[name="csrf-token"]'), 
            },
            success: function (data) {
                $('.success-mail').css('display','block');
                $('.send-mail').attr("disabled", false);
                $('.send-mail').html('<i class="fa fa-share"></i> Send Mail');
            }
        });
});
    
});
</script> --}}