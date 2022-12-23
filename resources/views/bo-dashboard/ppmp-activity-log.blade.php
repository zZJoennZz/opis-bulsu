@include('layout/header', ['title' => 'PPMP Activity Log | OPIS - BulSU e-PROCUREMENT'])
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
                <div class="table-responsive">
                    <table id="ppmp-activity-log" class="table table-sm caption-top">
                        <caption>PPMP Activity Log</caption>
                        <thead>
                            <tr>
                                <th style="width: 50%;">Activity</th>
                                <th style="width: 30%;">Date and Time</th>
                                <th style="width: 20%;">Show Changes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ppmp_histories as $history)
                                <tr>
                                    <td>
                                        <div class="fw-bold">PPMP Item: {{ $history->product_name }}</div>
                                        @foreach(json_decode($history->changes_summary) as $summary)
                                            <div class="mb-2 border p-1 rounded">{{ $summary }}</div>
                                        @endforeach
                                    </td>
                                    <td>{{ $history->created_at }}</td>
                                    <td>
                                        <button class="btn btn-info">Show comparison</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
<link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
@include('layout/datatable', ['tableId' => 'ppmp-activity-log'])
@include('layout/footer')