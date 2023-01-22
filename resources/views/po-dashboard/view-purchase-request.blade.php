@include('layout/header', ['title' => 'Purchase Request | OPIS - BulSU e-PROCUREMENT'])
@include('layout/member_header')
<div class="container-fluid">
    <div class="row">
        @include('layout/sidebar')
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="pt-3">
                <div class="card">
                    <div class="card-body">
                        @include('layout/breadcrumb',
                        [
                            'breadcrumbs' => [
                                ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
                                ['name' => 'Purchase Request <span class="badge bg-primary">' . Auth::user()->ppmp_year . '</span>']
                            ]
                        ]
                        )
                        <div class="mb-3">
                            <span class="float-end small"># of records: <span class="badge text-bg-secondary">{{ "2" }}</span></span>
                        </div>
                        <button onclick="printConsolidated()" type="button" class="btn btn-outline-success"><em class="bi bi-people-fill"></em> End Users List</button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
@include('layout/footer')