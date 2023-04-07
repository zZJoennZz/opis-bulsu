<x-dashboard-layout>
    <x-slot:title>
        Purchase Request
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Purchase Request <span class="badge bg-primary">' . Auth::user()->ppmp_year . '</span>']
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />

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
    @if (count($pr_records) === 0 && count($branches) === 0)
        <div class="mb-3">
            <div class="alert alert-warning" role="alert">
                This year's PPMP is not yet consolidated.
            </div>
        </div>
    @endif
    <div class="table-responsive">
        <table class="table table-small border-dark caption-top">
            <caption>Purchase Requests for the Year <span class="badge bg-primary">{{ Auth::user()->ppmp_year }}</span></caption>
            <thead>
                <tr class="small">
                    <th>PR #</th>
                    <th>Entity Name</th>
                    <th>Requested By</th>
                    <th>Date</th>
                    <th>Fund Cluster</th>
                    <th>Item Description</th>
                    <th>Quantity</th>
                    <th>Total Cost</th>
                    <th>Estimated Budget</th>
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
                        <td>{{ $pr->pr_items[0]->ppmp->source_of_fund->source_of_fund }}</td>
                        <td colspan="4"></td>
                        {{-- <td>
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
                        </td> --}}
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
                            <td colspan="5"></td>
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
                            <td>₱ {{ number_format($item->ppmp->item_detail->price_catalogue * $total_qty, 2) }}</td>
                            <td>₱ {{ number_format($item->ppmp->estimated_budget, 2) }}</td>
                            <td></td>
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
                <div class="modal-title fs-5" id="printPrLabel">Purchase Request Details</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="w-75 m-auto for-q-print" id="quotation-element">
                        <div>
                            <div class="row mb-4">
                                <div class="col-12 text-center fs-5 fw-bold text-uppercase">
                                    Purchase Request
                                </div>
                            </div>
                            <div class="row mb-3" style="font-size: 13px !important;">
                                <div class="col-6">
                                    <div class="d-flex">
                                        <div class="me-5" style="width: 30%;">Entity Name:</div> <div class="ms-1 border-bottom border-dark" style="width: 70%;" id="entity_name"></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex">
                                        <div class="me-5" style="width: 30%;">Fund Cluster:</div> <div class="ms-1 border-bottom border-dark" style="width: 70%;" id="source_of_fund"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <table class="table table-bordered border-dark table-sm" style="font-size: 13px !important;">
                                        <thead class="align-middle">
                                            <tr class="text-sm">
                                                <td colspan="2">
                                                    <div>
                                                        Office/Section:
                                                    </div>
                                                    <div class="fs-6 fw-bold text-center">
                                                        REGISTRAR
                                                    </div>
                                                </td>
                                                <td colspan="2">
                                                    <div class="d-flex">
                                                        <div class="w-25">PR. No.:</div> <div id="pr_number" class="w-75 border-bottom border-dark">ss</div>
                                                    </div>
                                                    <div class="d-flex">
                                                        <div class="w-25">Responsibility Center Code:</div> <div id="r_c_c" class="w-75 border-bottom border-dark">ss</div>
                                                    </div>
                                                </td>
                                                <td colspan="2">
                                                    <div class="d-flex">
                                                        <div class="w-25">Date:</div> <div id="pr_date" class="w-75 border-bottom border-dark">ss</div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 5%;" class="text-center">Stock/Property No.</th>
                                                <th style="width: 5%;" class="text-center">Unit</th>
                                                <th style="width: 40%;" class="text-center">Item Description</th>
                                                <th style="width: 16.67%;" class="text-center">Quantity</th>
                                                <th style="width: 16.67%;" class="text-center">Unit Cost</th>
                                                <th style="width: 16.67%;" class="text-center">Total Cost</th>
                                            </tr>
                                        </thead>
                                        <tbody id="pr_items_body">
                                            
                                        </tbody>
                                        <tfoot>
                                        <tr style="border: 0px !important;">
                                                <td colspan="6">
                                                    <div class="container-fluid">
                                                        <div class="row mb-3">
                                                            <div class="col-2">Purpose:</div>
                                                            <div class="col-10 m-auto border-bottom border-dark"> <span id="item_purposes"></span></div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
                                                    <div class="small text-center mb-3 fw-bold">Requested by:</div>
                                                    <div class="row small">
                                                        <div class="col-4 fw-bold text-end">Signature:</div>
                                                        <div class="col-7 border-bottom border-dark"></div>
                                                    </div>
                                                    <div class="row small">
                                                        <div class="col-4 fw-bold text-end">Printed Name:</div>
                                                        <div class="col-7 border-bottom border-dark"></div>
                                                    </div>
                                                    <div class="row small">
                                                        <div class="col-4 fw-bold text-end">Designation:</div>
                                                        <div class="col-7 text-center">University Registrar</div>
                                                    </div>
                                                </td>
                                                <td colspan="3">
                                                    <div class="small text-center mb-5">Approved</div>
                                                    <div class="text-center fw-bold">{{ getSettingValue('university_president') }}</div>
                                                    <div class="text-center fst-italic">University President</div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
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

    <x-slot:additional_script>
        @vite('resources/js/app.js')

        <script>
            async function toggleBranchPurchaseRequest(e) {
                const isChecked = e.target.checked;
                const branchesId = e.target.value;
                let data = {
                    mode: isChecked,
                    branches_id: branchesId,
                }
                await axios.post(`{{ route('pr.toggle') }}`, data)
                    .then(res => console.log("Success"))
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
                        let ctr = 0;
                        prData.pr_items.map(item => {
                            ctr += 1;
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
        
                            prTableContent += `
                                <tr class="align-middle">
                                    <td class="text-center">${ctr}</td>
                                    <td class="text-center">${item.ppmp.item_detail.unit.uom}</td>
                                    <td>${item.ppmp.item_detail.description}</td>
                                    <td class="text-center">${totalQty}</td>
                                    <td class="text-center">₱ ${item.ppmp.item_detail.price_catalogue}</td>
                                    <td class="text-end">₱ ${(item.ppmp.item_detail.price_catalogue * totalQty).toFixed(2)}</td>
                                </tr>
                            `;
                        })
                        for (let row = 0; row < (25 - prData.pr_items.length); row ++) {
                            prTableContent += `
                                <tr class="align-middle">
                                    <td class="text-center">-</td>
                                    <td class="text-center"></td>
                                    <td></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-end"></td>
                                </tr>
                            `;
                        }
                        let prDate = new Date(prData.created_at);
                        $('#pr_date').html(prDate.toLocaleDateString('en-PH', dateOptions));
                        $('#entity_name').html(prData.branch.branch_name);
                        $('#pr_number').html(prData.pr_number);
                        $('#source_of_fund').html(sourceOfFund);
                        $('#pr_items_body').html(prTableContent);
                        $('#item_purposes').html(prData.purpose);
                        $('#printPr').modal('toggle');
                    })
                    .catch(err => alert('Cannot fetch purchase request record.'));
            }
        </script>
        @include('layout/datatable', ['tableId' => 'branches-list'])
    </x-slot>
</x-dashboard-layout>