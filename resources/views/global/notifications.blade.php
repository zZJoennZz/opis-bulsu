@include('layout/header', ['title' => 'Notifications | OPIS - BulSU e-PROCUREMENT'])
@include('layout/member_header')
<div class="container-fluid">
    <div class="row">
        @include('layout/sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="p-3">
                @if (count($user_notif) === 0)
                    <p class="text-center text-muted"><em>No notifications for you</em></p>
                @endif
                @foreach ($user_notif as $notif)
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">{{ $notif->title }}
                                @if ($notif->is_read === 0)
                                    <span class="badge bg-danger rounded-pill">New!</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill">Read</span>
                                @endif
                            </h5>
                            <h6 class="card-subtitle text-muted">Submitted on: {{ $notif->created_at->format('m-d-Y H:i:s') }}</h6>
                            
                            <p class="card-text"><a href="{{ $notif->url }}">{{ $notif->message }}</a></p>
                            @if ($notif->is_read === 0)
                                <a href="{{ route('notification.read', ['notif_id' => $notif->id]) }}" type="button" class="btn btn-primary">Acknowledge</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </main>
    </div>
</div>
<link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
@include('layout/footer')