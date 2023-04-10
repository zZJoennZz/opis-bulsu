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
                        <h1 class="h5 card-title"> <span class="float-end small"># of records: <span class="badge text-bg-secondary">{{ count($quotations) }}</span></span></h1>
                        <div class="mb-4">
                            <a href="{{route('quotation.add')}}" class="btn btn-primary @if(count($pryears)<1) disabled @endif"> <em class="bi bi-folder-plus"></em> Add New Quotation</a>
                            <a href="{{route('quotation.summary')}}" class="btn btn-success @if(count($pryears)<1) disabled @endif"><em class="bi bi-file-spreadsheet-fill"></em> Quotation Summary Report</a>
                            <a href="{{route('company.all')}}" class="btn btn-secondary"><em class="bi bi-buildings"></em> Company Profiles</a>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-sm border-dark" id="quotation-list-table">
                                <caption>List of quotations</caption>
                                <thead>
                                    <tr>
                                        <th>Quotation #</th>
                                        <th>Company</th>
                                        <th>Total Price</th>
                                        <th>Date Added</th>
                                        <th style="width: 5%;" class="text-end">View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($quotations as $quotation)
                                        @php
                                            $totalAmount = 0;
                                            foreach($quotation->items as $item) {
                                                foreach($item->pr_item->ppmp->milestones as $milestone) {
                                                    $totalAmount += floatval($item->offered_unit_price) * intval($milestone->milestone_value);
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $quotation->quotation_number }}</td>
                                            <td>{{ $quotation->company->name }}</td>
                                            <td>₱ {{ number_format($totalAmount, 2) }}</td>
                                            <td>{{ date_format($quotation->created_at,"m/d/Y H:i A") }}</td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-primary" onclick="getQuotation({{ $quotation->id }})">
                                                    <em class="bi bi-eye-fill"></em>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- FOR PRINT --}}
                        <div class="modal fade" id="viewQuotation" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="viewQuotationLabel" aria-hidden="true">
                            <div class="modal-dialog modal-fullscreen">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="viewQuotationLabel">Quotation Details</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="w-75 m-auto for-q-print" id="quotation-element">
                                            <div class="row mb-3">
                                                <div class="col-2"></div>
                                                <div class="col-8 text-center fw-bold">
                                                    <img src="{{ asset('img/bsu-small-logo.png') }}" alt="bsu logo" width="100" style="float: left;" />
                                                    <div class="h-100 d-flex align-content-center flex-column justify-content-center">
                                                        <div>Republic of Philippines</div>
                                                        <div class="fs-4 mb-2">Bulacan State University</div>
                                                        <div class="fs-6">City of Malolos, Bulacan</div>
                                                    </div>
                                                </div>
                                                <div class="col-2"></div>
                                            </div>
                                            <div class="row mb-5">
                                                <div class="col-12 text-center text-uppercase fs-5">
                                                    Request for Quotation for the Procurement of Goods
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 text-danger mb-3">
                                                    *** Mandatory to fill in ***
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-6">
                                                    <div class="mb-2">
                                                        <span class="text-uppercase text-secondary">Company:</span> <span id="name"></span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <span class="text-uppercase text-secondary">Address:</span> <span id="full_address"></span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <span class="text-uppercase text-secondary">Contact No.:</span> <span id="contact_number"></span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <span class="text-uppercase text-secondary">TIN No.:</span> <span id="tin"></span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <span class="text-uppercase text-secondary">PhilGEPS Registration No.:</span> <span id="phil_reg_number"></span>
                                                    </div>
                                                    <div>
                                                        <span class="text-uppercase text-secondary">Email Address:</span> <span id="email_address"></span>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="mb-2">
                                                        <span class="text-uppercase text-secondary">Quotation No.:</span> <span id="quotation_number"></span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <span class="text-uppercase text-secondary">Purchases Request No.:</span> <span id="purchase_requests_number"></span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <span class="text-uppercase text-secondary">Purpose:</span> <span id="purpose"></span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <span class="text-uppercase text-secondary">ABC:</span> <span id="abc"></span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <span class="text-uppercase text-secondary">Delivery Period:</span> 7 calendar days upon receipt of purchase order.
                                                    </div>
                                                </div>
                                            </div>
                
                                            <div class="row mb-3">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <th class="text-center text-uppercase">Item No.</th>
                                                        <th class="text-center text-uppercase">Item & Specification</th>
                                                        <th class="text-center text-uppercase">Qty/Unit</th>
                                                        <th class="text-center text-uppercase">Brand and Model Offered</th>
                                                        <th class="text-center text-uppercase">Unit Price</th>
                                                        <th class="text-center text-uppercase">Total Price</th>
                                                    </thead>
                                                    <tbody id="quotation-items-list">
                
                                                    </tbody>
                                                </table>
                                            </div>
                
                                            <div class="row">
                                                <div class="col-6 text-center">
                                                    <div class="mb-5">Accomplished by: _______________________</div>
                                                    <div>_______________________</div>
                                                    <div>Supplier's Representative</div>
                                                    <div class="small text-secondary mb-5">(Print Name and Signature)</div>
                                                    <div>Date Accomplished: _______________________</div>
                                                </div>
                                                <div class="col-6 text-center">
                                                    <div class="mb-4 fs-5">
                                                        By the authority of the University President.
                                                    </div>
                                                    <div>_______________________</div>
                                                    <div>{{ getSettingValue('bac_chairman') }}</div>
                                                    <div class="small text-secondary mb-5">BAC Chairman</div>
                                                    <div>Canvassed by: _______________________</div>
                                                    <div class="small text-secondary">(Name and Signature)</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                    <button type="button" onclick="window.print()" class="btn btn-primary"><em class="bi bi-printer-fill"></em> Print</button>
                                    </div>
                                </div>
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
    async function getQuotation(quotationId) {
        await axios.get(`{{ route('quotation.single.api') }}/${quotationId}`)
            .then(res => {
                let quoteData = res.data.data[0];
                // console.log(quoteData);
                // return;
                $('#name').html(quoteData.company.name);
                $('#full_address').html(quoteData.company.full_address);
                $('#contact_number').html(quoteData.company.contact_number);
                $('#tin').html(quoteData.company.tin);
                $('#email_address').html(quoteData.company.email_address);
                $('#quotation_number').html(quoteData.quotation_number);
                $('#purchase_requests_number').html(quoteData.items[0].pr_item.pr.pr_number);
                let itemPurpose = '';
                let quoteItems = ``;
                quoteData.items.map(item => {
                    itemPurpose += (itemPurpose === '' ? '' : ',') + item.pr_item.ppmp.item_purpose.description;
                    let totalQty = 0;
                    item.pr_item.ppmp.milestones.map(milestone => totalQty += milestone.milestone_value)
                    quoteItems += `
                        <tr>
                            <td>${item.item_number}</td>
                            <td>${item.pr_item.ppmp.item_detail.description}</td>
                            <td>${totalQty} ${item.pr_item.ppmp.item_detail.unit.uom}</td>
                            <td>${item.brand_and_model_offered}</td>
                            <td>₱ ${item.offered_unit_price}</td>
                            <td>₱ ${(item.offered_unit_price * totalQty).toFixed(2)}</td>
                        </tr>
                    `;
                });
                $('#purpose').html(itemPurpose);
                $('#quotation-items-list').html(quoteItems);
                $('#viewQuotation').modal('toggle');
            })
            .catch(err => alert('Cannot fetch quotation data.'));
    }
</script>
<link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
@include('layout/datatable', ['tableId' => 'quotation-list-table'])
@include('layout/footer')
