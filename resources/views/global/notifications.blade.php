@include('layout/header', ['title' => 'Notifications | OPIS - BulSU e-PROCUREMENT'])
@include('layout/member_header')
<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3 sidebar-sticky">
                @if (Auth::user()->account_type === "admin")
                    <div class="p-2">
                        <div class="alert alert-primary d-flex align-items-center" role="alert">
                            <em class="bi bi-info-circle-fill"></em>
                            <div class="ms-2">
                                Admin View
                            </div>
                        </div>
                    </div>
                @endif
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('notification.show') }}">
                            <em class="bi bi-patch-exclamation-fill"></em>
                            Notification(s)
                            @if (count(Auth::user()->notifications->where('is_read', '=', 0)) > 0)
                                <span class="badge text-bg-danger">New!</span>
                            @endif
                        </a>
                    </li>
                </ul>
                <hr />
                @if (Auth::user()->account_type === "admin" || Auth::user()->account_type === "END_USER")
                    <ul class="nav flex-column">
                        @include('layout/enduser_sidebar')
                    </ul>
                    <hr />
                @endif

                @if (Auth::user()->account_type === "admin" || Auth::user()->account_type === "BUDGET_OFFICE")
                    <ul class="nav flex-column">
                        @include('layout/bo_sidebar')
                    </ul>
                    <hr />
                @endif
                
                @if (Auth::user()->account_type === "admin" || Auth::user()->account_type === "PROCUREMENT_OFFICE")
                    
                    <ul class="nav flex-column">
                        @include('layout/po_sidebar')
                    </ul>
                    <hr />
                @endif
            </div>
        </nav>

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