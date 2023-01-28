<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3 sidebar-sticky scroll--simple">
        <div class="p-3 bg-dark text-light mb-3">
            <div class="fs-5 mb-3">
                <a href="{{ route('logout.perform') }}">
                    <div class="float-end bg-danger rounded-circle" style="display: flex; background: gray; width: 40px; height: 40px;">
                        <div class="m-auto text-light"><em class="bi bi-box-arrow-right"></em></div>
                    </div>
                </a>
                @if(Auth::user()->account_type === 'PROCUREMENT_OFFICE' || Auth::user()->account_type === 'admin')
                    <i class="bi bi-shield-check"></i>
                @elseif(Auth::user()->account_type === 'BUDGET_OFFICE')
                    <i class="bi bi-cash-stack"></i>
                @elseif(Auth::user()->account_type === 'END_USER')
                    <i class="bi bi-person"></i>
                @endif
                Hi, <span class="fw-bold">{{ Auth::user()->profile->first_name }}</span>!
                    <a href="{{ route('notification.show') }}">
                    @if (count(Auth::user()->notifications->where('is_read', '=', 0)) > 0)
                        <div class="d-inline" style="position: relative;">
                            <em class="bi bi-bell-fill text-light"></em>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.7rem">
                                @php
                                    echo count(Auth::user()->notifications->where('is_read', '=', 0))
                                @endphp
                            </span>
                        </div>
                    @else
                        <em class="bi bi-bell text-secondary"></em>
                    @endif
                </a>
            </div>
            <div>
                <a href="{{ route('account-settings.show') }}" class="text-light"><em class="bi bi-sliders"></em> Account Settings</a>
            </div>
        </div>
        @if (Auth::user()->account_type === "admin")
            <div class="p-2">
                <div class="alert alert-primary d-flex align-items-center" role="alert">
                    <em class="bi bi-info-circle-fill"></em>
                    <div class="ms-3">
                        Admin View <div class="small fst-italic">You can see every user type's dashboard.</div>
                    </div>
                </div>
            </div>
        @endif
        @if (intval(getSettingValue('maintenance_mode')) === 1)
            <div class="p-2">
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <em class="bi bi-info-circle-fill"></em>
                    <div class="ms-3">
                        System is currently in maintenance mode.
                    </div>
                </div>
            </div>
        @endif
        {{-- <ul class="nav flex-column">
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
        </ul> --}}
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