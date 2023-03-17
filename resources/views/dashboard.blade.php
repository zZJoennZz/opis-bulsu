    @include('layout/header', ['title' => 'OPIS - BulSU e-PROCUREMENT'])
    <style>
        .ppmpCard {
            transition: ease-in-out all 200ms;
            border: 1px solid transparent;
        }
        .ppmpCard:hover {
            box-shadow: none !important;
            border-bottom: 1px solid rgb(209, 209, 209) !important;
        }
    </style>
        @include('layout/member_header')
        <div class="container-fluid">
            <div class="row">
                @include('layout/sidebar')

                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div class="mt-3 card">
                        <div class="card-body">
                            @include('layout/breadcrumb',
                            [
                                'breadcrumbs' => [['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show']]
                            ]
                            )
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

                            @if (Auth::user()->account_type === "admin" || Auth::user()->account_type === "SUPPLY_OFFICE")
                                @include('layout/so_dashboard')
                                <hr />
                            @endif
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
    @include('layout/footer')