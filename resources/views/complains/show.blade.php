@extends('layouts.app')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Complaints Discussion</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="container-fluid">

        <div class="row mt-3">
            <div class="col-12">
                <div class="list-group">
                    <div class="list-group-item">
                        <div class="row">
                            <div class="col px-4">
                                <div>
                                    <div class="float-right">{{ $complain->created_at }}&nbsp;&nbsp;&nbsp;<span
                                            class="badge badge-success">{{ Carbon\Carbon::parse($complain->created_at)->diffForHumans() }}</span>
                                    </div>
                                    <h3>{{ $complain->title }}</h3>
                                    <p class="mb-0">{{ $complain->description }}</p>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br />
    <section class="content">
        <div class="col-md-4 float-right">
            <div class="card card-primary collapsed-card">
                <div class="card-header" style="color: black;background: #ebc311;border: black;">
                    <h3 class="card-title">Add a Note <button type="button" class="btn btn-tool"
                            data-card-widget="collapse" title="Collapse">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </button></h3>
                    <div class="card-tools mt-2">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body" style="display: none;">
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
                    <form action="{{ route('complains.add_notes') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{-- Hidden --}}
                        <div>
                            <input type="hidden" name="complain_id" value="{{ $complain->id }}">
                        </div>
                        {{-- End Hidden --}}
                        <div class="form-group">
                            <label for="note">Write a Note</label>
                            <textarea type="text" name="note" id="note" class="form-control" placeholder="Write here ..." required></textarea>
                        </div>
                        <label for="exampleInputFile">Attach a file</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" accept=".jpg,.png,.jpeg" name="note_document"
                                    class="custom-file-input" id="exampleInputFile">
                                <label class="custom-file-label" for="exampleInputFile">Choose
                                    file</label>
                            </div>
                            <div class="input-group-append">
                                <span style="display: none;" class="input-group-text">Upload</span>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </section>
    <section class="content">
        @foreach ($complain_notes as $note)
            <div class="container-fluid">

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="container-fluid">
                                <!-- Timelime example  -->
                                <div class="row">
                                    <div class="col-md-12 ">
                                        <!-- The time line -->
                                        <div class="timeline">
                                            @php
                                                $auth_name = DB::table('users')
                                                    ->where('id', $note->created_by)
                                                    ->first();
                                            @endphp
                                            <div>
                                                @if ($note->created_by == auth()->user()->id)
                                                    <i class="far fa-comment bg-blue" style=" margin-top: 3px;"></i>
                                                @else
                                                    <i class="far fa-comment bg-yellow" style="margin-top: 3px;  "></i>
                                                @endif
                                                <div class="timeline-item ">
                                                    <span class="time"><i class="fas fa-clock"></i>
                                                        {{ $note->created_at }}&nbsp;&nbsp;<span
                                                            class="badge badge-info">{{ Carbon\Carbon::parse($note->created_at)->diffForHumans() }}</span></span>
                                                    <h3 class="timeline-header"><a href="#">
                                                            @if ($note->created_by == auth()->user()->id)
                                                                You
                                                            @else
                                                                {{ $auth_name->name }}
                                                            @endif
                                                            @if ($auth_name->type ==
                                                                auth()->user()->getRoleNames()[0])
                                                            @else
                                                                @if ($auth_name->type == 'Superadmin')
                                                                    <span class="badge badge-info"> Admin</span>
                                                                @else
                                                                    <span class="badge badge-info">
                                                                        {{ $auth_name->type }}</span>
                                                                @endif
                                                            @endif

                                                        </a> added a note</h3>

                                                    <div class="timeline-body">
                                                        {{ $note->note }}
                                                    </div>
                                                    @if ($note->note_document !== 'null')
                                                        <p class="timeline-header "><a href="#">
                                                                @if ($note->created_by == auth()->user()->id)
                                                                    You
                                                                @else
                                                                    {{ $auth_name->name }}
                                                                @endif
                                                            </a> added a attachment</p>
                                                        <div class="timeline-body media">
                                                        </div>
                                                        <img src="{{ asset('uploads/' . $note->note_document) }}"
                                                            alt="image" class="align-self-center"
                                                            style="height: 200px;width: 200px;margin-left: 38px;margin-bottom: 30px;">
                                                </div>
                                            @else
        @endif
        </div>


        </div>
        </div>
        </div>
        <!-- /.col -->
        </div>
        </div>
        <!-- /.timeline -->
        </div>
        </div>

        </div>
        @endforeach

    </section>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script>
    function showhide(val) {
        (val == 'N') ? $('.authorized-person').hide(): $('.authorized-person').show();
    }
    $(document).ready(function() {
        bsCustomFileInput.init();
    });
</script>
