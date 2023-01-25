<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3 sidebar-sticky scroll--simple">
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
                    @if (count(Auth::user()->notifications->where('is_read', '=', 0)) > 0)
                        <em class="bi bi-circle-square text-danger"></em>
                    @else
                        <em class="bi bi-app text-secondary"></em>
                    @endif
                    Notification(s)
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
        <ul class="nav flex-column">
            <li class="nav-link">
                <div class="mb-3 mb-md-0 small text-muted">
                    Copyright © {{ date("Y") }}. Bulacan State University.
                </div>
            </li>
        </ul>
        <hr />
    </div>
</nav>