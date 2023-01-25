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
                                                    <button
                                                        class="btn btn-secondary"
                                                        type="button"
                                                    >
                                                        <em class="bi bi-printer-fill"></em></button>
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
</script>
@include('layout/datatable', ['tableId' => 'branches-list'])
@include('layout/footer')