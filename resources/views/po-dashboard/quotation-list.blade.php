@include('layout/header', ['title' => 'Price Quotations | OPIS - BulSU e-PROCUREMENT'])
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
                                ['name' => 'Price Quotations'],
                            ]
                        ]
                        )
                        <h1 class="h5 card-title"> <span class="float-end small"># of records: <span class="badge text-bg-secondary">{{ count([1,2,3,4]) }}</span></span></h1>
                        <div class="mb-4">
                            <a href="{{route('quotation.add')}}" class="btn btn-primary"><em class="bi bi-folder-plus"></em> Add New Quotation</a>
                            <a href="#" class="btn btn-success"><em class="bi bi-file-spreadsheet-fill"></em> Quotation Summary Report</a>
                            <a href="{{route('company.all')}}" class="btn btn-secondary"><em class="bi bi-buildings"></em> Company Profiles</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered" id="quotation-list-table">
                                <caption>List of quotations</caption>
                                <thead>
                                    <tr>
                                        <th>Quotation #</th>
                                        <th>Company</th>
                                        <th>Total Price</th>
                                        <th>Date Added</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<script src="{{ asset('build/assets/app.b487754a.js') }}"></script>
<link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
@include('layout/datatable', ['tableId' => 'quotation-list-table'])
@include('layout/footer')
