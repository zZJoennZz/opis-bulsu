@include('layout/header', ['title' => 'Notifications | OPIS - BulSU e-PROCUREMENT'])
@include('layout/member_header')
<div class="container-fluid">
    <div class="row">
        @include('layout/sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="pt-4">
                <div class="card mb-4">
                    <div class="card-body table-responsive small">
                        <table class="table table-small" id="notification-table">
                            <thead>
                                <tr>
                                    <th>Notification/s</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($user_notif as $notif)
                                    <tr>
                                        <td>
                                            <div class="card mb-3">
                                                <div class="card-body">
                                                    <p class="card-title fw-bold">{{ $notif->title }}
                                                        @if ($notif->is_read === 0)
                                                            <span class="badge bg-danger rounded-pill">New!</span>
                                                        @else
                                                            <span class="badge bg-secondary rounded-pill">Read</span>
                                                        @endif
                                                    </p>
                                                    <p class="card-subtitle text-muted">Submitted on: {{ $notif->created_at->format('m-d-Y H:i:s') }}</p>
                                                    
                                                    <p class="card-text"><a href="{{ $notif->url }}">{{ $notif->message }}</a></p>
                                                    @if ($notif->is_read === 0)
                                                        <a href="{{ route('notification.read', ['notif_id' => $notif->id]) }}" type="button" class="btn btn-primary">Acknowledge</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.13.1/cr-1.6.1/r-2.4.0/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.13.1/cr-1.6.1/r-2.4.0/datatables.min.js"></script>
<script defer>
    $(document).ready(function() {
        $('#notification-table').DataTable({
            "searching" : false
        });
    });
</script>
@include('layout/footer')