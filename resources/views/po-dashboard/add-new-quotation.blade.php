@include('layout/header', ['title' => 'Add New Quotation | OPIS - BulSU e-PROCUREMENT'])
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
                                ['name' => 'Price Quotations', 'route' => 'quotation.all'],
                                ['name' => 'Add New Quotation'],
                            ]
                        ]
                        )
                        <h1 class="h5 text-uppercase text-center text-secondary">Request for Quotation for the Procurement of Goods</h1>
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="mb-3">
                                    <span class="text-danger">*** Mandatory to fill in ***</span>
                                </div>
                                <div class="mb-3">
                                    <span class="fst-italic text-secondary">Quotation # will be generated after submission</span>
                                </div>
                                <div class="mb-3">
                                    <label for="companies_id" class="form-label">Company</label>
                                    <select onchange="get_company_details(event)" id="companies_id" name="companies_id" class="form-select" aria-label="Company name">
                                        <option selected>Select company</option>
                                        @foreach ($company_profiles as $profile)
                                            <option value="{{$profile->id}}">{{$profile->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="purchase_requests_id" class="form-label">Purchase Request #</label>
                                    <select id="purchase_requests_id" name="purchase_requests_id" class="form-select" aria-label="Purchase request number">
                                        <option selected>Select purchase request</option>
                                        @foreach ($purchase_requests as $pr)
                                            <option value="{{$pr->id}}">{{$pr->pr_number}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <span class="text-danger"><span class="fw-bold">Delivery Period:</span> 7 calendar days upon receipt of <span class="fw-bold fst-italic">purchase order</span></span>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6" id="company-details">
                                <div><span class="placeholder col-10"></span></div>
                                <div class="mb-3"><span class="placeholder col-12"></span></div>
                                <div><span class="placeholder col-10"></span></div>
                                <div class="mb-3"><span class="placeholder col-12"></span></div>
                                <div><span class="placeholder col-10"></span></div>
                                <div class="mb-3"><span class="placeholder col-12"></span></div>
                                <div><span class="placeholder col-10"></span></div>
                                <div class="mb-3"><span class="placeholder col-12"></span></div>
                                <div><span class="placeholder col-10"></span></div>
                                <div><span class="placeholder col-12"></span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<script src="{{ asset('build/assets/app.b487754a.js') }}"></script>
<script>
    async function get_company_details(event) {
        await axios.get(`{{ route('company.single.api') }}/${event.target.value}`)
            .then(res => {
                let companyDetails = res.data.data[0];
                let htmlContent = `
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered">
                            <tr><th>Company</th><td>${companyDetails.name}</td></tr>
                            <tr><th>Address</th><td>${companyDetails.full_address}</td></tr>
                            <tr><th>Contact #</th><td>${companyDetails.contact_number}</td></tr>
                            <tr><th>TIN #</th><td>${companyDetails.tin}</td></tr>
                            <tr><th>Email Address</th><td>${companyDetails.email_address}</td></tr>
                        </table>
                    </div>
                `;
                $('#company-details').html(htmlContent);
            })
            .catch(err => alert(`Cannot fetch company profile. Please reload the page.`));
    }
</script>
<link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
@include('layout/datatable', ['tableId' => 'quotation-list-table'])
@include('layout/footer')
