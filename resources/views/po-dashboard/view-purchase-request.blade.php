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
                            <span class="float-end small"># of records: <span class="badge text-bg-secondary">{{ count($pr_records) }}</span></span>
                        </div>
                        <div class="mb-3">
                            <button data-bs-toggle="modal" data-bs-target="#enduserslist" type="button" class="btn btn-outline-success"><em class="bi bi-people-fill"></em> End Users List</button>
                        </div>
                        <div class="modal fade" id="enduserslist" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="enduserslistLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="enduserslistLabel">End Users List</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="table-responsive">
                                            <table id="branches-list" class="table table-small table-bordered">
                                                <caption></caption>
                                                <thead>
                                                    <tr>
                                                        <th>Entity</th>
                                                        <th>PR Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($branches as $branch)
                                                        <tr>
                                                            <td>{{$branch->branch_name}}</td>
                                                            <td class="d-flex align-items-center justify-content-center">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox" role="switch" id="{{$branch->id}}pr-switch" @if (count($branch->pr_mode) > 0 && $branch->pr_mode[0]->mode === "ENABLED") checked @endif value="{{$branch->id}}" onclick="toggleBranchPurchaseRequest(event)">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-small table-bordered">
                                <caption>Purchase Requests for the Year <span class="badge bg-primary">{{ Auth::user()->ppmp_year }}</span></caption>
                                <thead>
                                    <tr class="small">
                                        <th>PR #</th>
                                        <th>Entity Name</th>
                                        <th>Requested By</th>
                                        <th>Date</th>
                                        <th>Item Description</th>
                                        <th>Quantity</th>
                                        <th>Total Cost</th>
                                        <th>Estimated Budget</th>
                                        <th>Fund Cluster</th>
                                        <th>Responsibility Center</th>
                                        <th>Approve</th>
                                        <th>Print</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pr_records as $pr)
                                        <tr>
                                            <td>{{ $pr->pr_number }}</td>
                                            <td>{{ $pr->branch->branch_name }}</td>
                                            <td>{{ $pr->requester->profile->first_name }} {{ $pr->requester->profile->last_name }}</td>
                                            <td>{{ date("m-d-Y", strtotime($pr->created_at)) }}</td>
                                            <td colspan="6"></td>
                                            <td>
                                                <div class="d-flex justify-content-center align-items-center">
                                                    @if ($pr->is_approve === 0)
                                                        <button
                                                            class="btn btn-primary"
                                                            type="button"
                                                            onclick="approvePr({{$pr->id}})"
                                                        >
                                                            <em class="bi bi-check-circle"></em>
                                                        </button>
                                                    @else
                                                        <button
                                                            class="btn btn-primary"
                                                            type="button"
                                                            disabled
                                                        >
                                                            <em class="bi bi-check-circle"></em>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center align-items-center">
                                                    @if ($pr->is_approve === 1)
                                                        <button
                                                            class="btn btn-secondary"
                                                            type="button"
                                                            onclick="getPr({{$pr->id}})"
                                                        >
                                                            <em class="bi bi-printer-fill"></em>
                                                        </button>
                                                    @else
                                                        <button
                                                            class="btn btn-secondary"
                                                            type="button"
                                                            disabled
                                                        >
                                                            <em class="bi bi-printer-fill"></em>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @foreach ($pr->pr_items as $item)
                                            <tr>
                                                <td colspan="4"></td>
                                                <td>{{ $item->ppmp->item_detail->description }}</td>
                                                <td>
                                                    @php
                                                        $total_qty = 0;
                                                        foreach($item->ppmp->milestones as $milestone) {
                                                            $total_qty += $milestone->milestone_value;
                                                        }
                                                    @endphp
                                                    {{ $total_qty }}
                                                </td>
                                                <td>{{ number_format($item->ppmp->item_detail->price_catalogue * $total_qty, 2) }}</td>
                                                <td>{{ number_format($item->ppmp->estimated_budget, 2) }}</td>
                                                <td>{{ $item->ppmp->source_of_fund->source_of_fund }}</td>
                                                <td><span class="text-secondary fst-italic">Placeholder</span></td>
                                                <td colspan="2"></td>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        {{-- FOR PRINT --}}
                        <div class="modal fade" id="printPr" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="printPrLabel" aria-hidden="true">
                            <div class="modal-dialog modal-fullscreen">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="printPrLabel">Purchase Request Details</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="w-75 m-auto for-q-print" id="quotation-element">
                                            <div>
                                                <div class="row mb-4">
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
                                                <div class="row mb-4">
                                                    <div class="col-12 text-center fs-5 fw-bold text-uppercase">
                                                        Purchase Request
                                                    </div>
                                                </div>
                                                <div class="row mb-3 small">
                                                    <div class="col-6">
                                                        <div>
                                                            <span class="text-secondary">Entity Name:</span> <span id="entity_name"></span>
                                                        </div>
                                                        <div>
                                                            <span class="text-secondary">Office/Section:</span> _______________________ <span class="text-secondary">PR No.:</span> <span id="pr_number"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div>
                                                            <span class="text-secondary">Fund Cluster:</span> <span id="source_of_fund"></span>
                                                        </div>
                                                        <div>
                                                            <span class="text-secondary">Responsibility Center Code:</span> _______________________ <span class="text-secondary">Date:</span> <span id="pr_date"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-12">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center">Stock/Property No.</th>
                                                                    <th class="text-center">Item Description</th>
                                                                    <th class="text-center">Quantity</th>
                                                                    <th class="text-center">Unit</th>
                                                                    <th class="text-center">Unit Cost</th>
                                                                    <th class="text-center">Total Cost</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="pr_items_body">
                                                                
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-1">Purpose:</div>
                                                    <div class="col-11 m-auto border-bottom border-dark"> <span id="item_purposes"></span></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6 border border-dark p-3">
                                                        <div class="small text-center mb-3">Requested by:</div>
                                                        <div class="row small">
                                                            <div class="col-4 fw-bold text-end">Signature:</div>
                                                            <div class="col-7 border-bottom border-dark"></div>
                                                        </div>
                                                        <div class="row small">
                                                            <div class="col-4 fw-bold text-end">Printed Name:</div>
                                                            <div class="col-7 border-bottom border-dark"></div>
                                                        </div>
                                                        <div class="row small">
                                                            <div class="col-4 fw-bold text-end">Disignation:</div>
                                                            <div class="col-7 text-center">AO V. Procurement Officer</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 border-top border-end border-bottom border-dark p-3">
                                                        <div class="small text-center mb-5">Approved</div>
                                                        <div class="text-center fw-bold">{{ getSettingValue('university_president') }}</div>
                                                        <div class="text-center fst-italic">University President</div>
                                                    </div>
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
<link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
<script src="{{asset('build/assets/app.b487754a.js')}}"></script>
<script>
    async function toggleBranchPurchaseRequest(e) {
        const isChecked = e.target.checked;
        const branchesId = e.target.value;
        let data = {
            mode: isChecked,
            branches_id: branchesId,
        }
        await axios.post(`{{ route('pr.toggle') }}`, data)
            .then(res => console.log(res))
            .catch(err => alert('Toggling the PR mode didn\'t work. Please refresh the page. If the problem persists, please report to web administrator.'));
    }

    async function approvePr(prId) {
        await axios.post(`{{ route('pr-approve.api') }}/${prId}`)
            .then(res => {
                console.log(res);
                window.location.reload();
            })
            .catch(err => {
                console.log(err);
                window.location.reload();
            });
    }

    async function getPr(prId) {
        const dateOptions = { year: 'numeric', month: 'numeric', day: 'numeric' };
        await axios.get(`{{ route('pr-single.api') }}/${prId}`)
            .then(res => {
                let prData = res.data[0];
                let sourceOfFund = '';
                let sourceOfFundArr = [];
                let prTableContent = '';
                let itemPurposes = '';
                prData.pr_items.map(item => {
                    
                    if (!sourceOfFundArr.includes(item.ppmp.source_of_fund.source_of_fund)) {
                        if (sourceOfFund === '') {
                        sourceOfFund += item.ppmp.source_of_fund.source_of_fund;
                        } else {
                            sourceOfFund += ', ' + item.ppmp.source_of_fund.source_of_fund;
                        }
                    }

                    sourceOfFundArr.push(item.ppmp.source_of_fund.source_of_fund);

                    let totalQty = 0;
                    item.ppmp.milestones.map(m => {
                        totalQty += m.milestone_value;
                    });

                    
                    if (itemPurposes === '') {
                        itemPurposes += item.ppmp.item_purpose.description;
                    } else {
                        itemPurposes += ', ' + item.ppmp.item_purpose.description;
                    }

                    prTableContent += `
                        <tr>
                            <td>1</td>
                            <td>${item.ppmp.item_detail.description}</td>
                            <td>${totalQty}</td>
                            <td>${item.ppmp.item_detail.unit.uom}</td>
                            <td>₱ ${item.ppmp.item_detail.price_catalogue}</td>
                            <td>₱ ${(item.ppmp.item_detail.price_catalogue * totalQty).toFixed(2)}</td>
                        </tr>
                    `;
                })
                let prDate = new Date(prData.created_at);
                $('#pr_date').html(prDate.toLocaleDateString('en-PH', dateOptions));
                $('#entity_name').html(prData.branch.branch_name);
                $('#pr_number').html(prData.pr_number);
                $('#source_of_fund').html(sourceOfFund);
                $('#pr_items_body').html(prTableContent);
                $('#item_purposes').html(itemPurposes);
                $('#printPr').modal('toggle');
            })
            .catch(err => alert('Cannot fetch purchase request record.'));
    }
</script>
@include('layout/datatable', ['tableId' => 'branches-list'])
@include('layout/footer')