    @include('layout/header', ['title' => 'OPIS - BulSU e-PROCUREMENT'])
        @include('layout/member_header')
        <div class="container-fluid">
            <div class="row">
                @include('layout/sidebar')

                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div class="mt-3 card">
                        <div class="card-body">
                            @if (Auth::user()->account_type === "admin" || Auth::user()->account_type === "END_USER")
                                @include('layout/enduser_dashboard')
                                <hr />
                            @endif

                            @if (Auth::user()->account_type === "admin" || Auth::user()->account_type === "BUDGET_OFFICE")
                                @include('layout/bo_dashboard')
                                <hr />
                            @endif

                            @if (Auth::user()->account_type === "admin" || Auth::user()->account_type === "PROCUREMENT_OFFICE")
                                @include('layout/po_dashboard')
                                <hr />
                            @endif
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
    @include('layout/footer')