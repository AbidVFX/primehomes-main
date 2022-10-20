@php
$auth_role = auth()
    ->user()
    ->getRoleNames()[0];
@endphp
@if ($auth_role == 'Superadmin')

    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
            @isset($notifications)
                @if ($notifications->count() == 0)
                @else
                    <span class="badge badge-danger navbar-badge" id="total_count">
                        {{ $notifications->count() }}
                    </span>
                @endif
            @endisset
            {{-- Hidden Total Count --}}
            <span class="badge badge-danger navbar-badge" id="total_count_show" style="display: none">
                0
            </span>
            {{-- End Hidden Total Count --}}
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="all-notifications">
            @isset($notifications)

                @if ($notifications->count() > 0)
                    <span class="dropdown-header">{{ $notifications->count() }} Notifications</span>
                    <div class="dropdown-divider"></div>

                    <!-- Message Start -->
                    @foreach ($notifications as $notification)
                        <a href="/complains" target="_blank" class="dropdown-item" id="mark">
                            <i class="fas fa-user-clock"></i>&nbsp;&nbsp; {{ Str::limit($notification->title, 20) }}
                            <span
                                class="float-button text-muted text-sm">{{ Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</span>

                        </a>
                        <div class="dropdown-divider"></div>
                    @endforeach

                    <!-- Message End -->
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer" id="mark-all">
                        Mark all as read
                    </a>
                @else
                    <p class="dropdown-item" id="no-notifications">There are no new notifications</p>
                @endif
            @endisset
        </div>


    </li>
@elseif ($auth_role == 'Owner' || $auth_role == 'Tenant')
    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
            @isset($notifications)
                @if ($notifications->count() == 0)
                @else
                    <span class="badge badge-danger navbar-badge" id="owner-total_count">
                        {{ $notifications->count() }}
                    </span>
                @endif
            @endisset
            {{-- Hidden Total Count --}}
            <span class="badge badge-danger navbar-badge" id="owner-total_count_show" style="display: none">
                0
            </span>
            {{-- End Hidden Total Count --}}
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="owner-all-notifications">
            @isset($notifications)

                @if ($notifications->count() > 0)
                    <span class="dropdown-header">{{ $notifications->count() }} Notifications</span>
                    <div class="dropdown-divider"></div>

                    <!-- Message Start -->
                    @foreach ($notifications as $notification)
                        @if ($notification->status !== 'pending')
                            <a href="/complains" target="_blank" class="dropdown-item dropdown-item-title" id="mark">
                                <i class="fas fa-user-clock"></i>&nbsp;&nbsp;
                                {{ $notifications->count() }} updates in Complaint
                                <span
                                    class="float-button text-muted text-sm">{{ Carbon\Carbon::parse($notification->updated_at)->diffForHumans() }}</span>
                            </a>
                            <div class="dropdown-divider"></div>
                        @else
                        @endif
                    @endforeach

                    <!-- Message End -->
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer" id="owner-mark-all">
                        Mark all as read
                    </a>
                @else
                    <p class="dropdown-item" id="owner-no-notifications">There are no new notifications</p>
                @endif
            @endisset
        </div>


    </li>
@else
@endif

{{-- @if ($notifications->count() > 0)
            @foreach ($notifications as $notification)
                <div class="alert alert-light" role="alert">
                    <p class="dropdown-item"><b> {{ $notification->data['name'] }} </b>&nbsp;
                        (c) has just registered.
                        [{{ date('j \\ F Y, g:i A', strtotime($notification->created_at)) }}]</p>
                    <a href="#"><button type="button" rel="tooltip" title="Mark as read"
                            class="btn btn-danger btn-link btn-sm mark-as-read" data-id="{{ $notification->id }}">
                            <i class="material-icons">close</i>
                        </button>
                    </a>
                </div>
                <hr>
            @endforeach
            <a href="#" class="dropdown-item" id="mark-all">
                Mark all as read
            </a>
        @else
            <p class="dropdown-item">There are no new notifications</p>
        @endif --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#mark-all").click(function(e) {
            $('#all-notifications').hide();
            $('#total_count').hide();

            e.preventDefault();
            var url = '{{ route('mark-all.update') }}';
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    if (data.success) {
                        window.location.reload(true);

                    } else {
                        printErrorMsg(data)
                    }
                },
            });
        });

        function printErrorMsg(msg) {
            $('.error-msg').find('p').html('');
            $('.error-msg').css('display', 'block');
            $.each(msg, function(key, value) {
                $(".error-msg").find("p").append('<li>' + value + '</li>');
            });
        }
    });
</script>


<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#owner-mark-all").click(function(e) {
            $('#owner-all-notifications').hide();
            $('#owner-total_count').hide();

            e.preventDefault();
            var url = '{{ route('owner-mark-all.update') }}';
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    if (data.success) {
                        window.location.reload(true);

                    } else {
                        printErrorMsg(data)
                    }
                },
            });
        });

        function printErrorMsg(msg) {
            $('.error-msg').find('p').html('');
            $('.error-msg').css('display', 'block');
            $.each(msg, function(key, value) {
                $(".error-msg").find("p").append('<li>' + value + '</li>');
            });
        }
    });
</script>
