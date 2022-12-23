<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h2 class="h3">Procurement Dashboard</h2>
</div>
<div class="mb-3 table-responsive" style="max-height: 90vh;">
    <table id="po-ppmp-table" class="table table-sm border-dark caption-top">
        <caption>Project Procure Management Plan - Dashboard</caption>
        <thead>
            <tr>
                <th style="width: 10%">PPMP Year</th>
                <th style="width: 30%">End-User / Unit</th>
                <th style="width: 15%">Requested By</th>
                <th style="width: 40%">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ppmp_list1 as $ppmp)
                <tr>
                    <td class="fs-3 fw-bold"><span class="badge bg-primary">{{ Auth::user()->ppmp_year }}</span></td>
                    <td class="fs-3 fw-bold">{{ $ppmp->branch_name }}</td>
                    <td>{{ $ppmp->username }}</td>
                    <td>
                        <div class="row p-2">
                            <div class="col-lg-4 col-md-12">
                                @php($branchId = $ppmp->id)
                                @php(
                                    $newBudgetRequests = array_filter($new_budget_requests1, function ($rec) use ($branchId) {
                                        return $rec["branches_id"] === $branchId;
                                    })
                                )
                                @foreach ($newBudgetRequests as $count)
                                    @if ($count["count"] > 0)
                                    <a href="{{ route('po-ppmp-approval.show', ['branch_id' => $ppmp->id]) }}" class="btn h-100 btn-primary position-relative" style="font-size: 1rem;">
                                        <em class="bi bi-file-earmark-spreadsheet" style="font-size: 2rem;"></em>
                                        <div class="fw-bold">New PPMP Approval</div>
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            {{ $count["count"] }}
                                            <span class="visually-hidden">new requests</span>
                                        </span>
                                    </a>
                                    @else
                                    <a class="btn opacity-50 h-100 btn-primary position-relative" style="font-size: 1rem;">
                                        <em class="bi bi-file-earmark-spreadsheet" style="font-size: 2rem;"></em>
                                        <div class="fw-bold">No New PPMP Approval</div>
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            {{ $count["count"] }}
                                            <span class="visually-hidden">new requests</span>
                                        </span>
                                    </a>
                                    @endif
                                @endforeach
                            </div>
                            <div class="col-lg-4 col-md-12">
                                @php(
                                    $approvedBudgetRequests = array_filter($approved_budget_request1, function ($rec) use ($branchId) {
                                        return $rec["branches_id"] === $branchId;
                                    })
                                )
                                @foreach ($approvedBudgetRequests as $count)
                                    @if ($count["count"] > 0)
                                        <a href="{{ route('po-approved-ppmp.show', ['branch_id' => $ppmp->id]) }}" class="btn h-100 btn-success position-relative" style="font-size: 1rem;">
                                            <em class="bi bi-journal-check" style="font-size: 2rem;"></em>
                                            <div class="fw-bold">Approved PPMP</div>
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                {{ $count["count"] }}
                                                <span class="visually-hidden">approved requests</span>
                                            </span>
                                        </a>
                                    @else
                                        <a class="btn h-100 opacity-50 btn-success position-relative" style="font-size: 1rem;">
                                            <em class="bi bi-journal-check" style="font-size: 2rem;"></em>
                                            <div class="fw-bold">Approved PPMP</div>
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                {{ $count["count"] }}
                                                <span class="visually-hidden">approved requests</span>
                                            </span>
                                        </a>
                                    @endif
                                @endforeach
                                
                            </div>
                            <div class="col-lg-4 col-md-12">
                                <button class="btn h-100 btn-secondary position-relative" style="font-size: 1rem;">
                                    <em class="bi bi-file-earmark-text" style="font-size: 2rem;"></em>
                                    <div class="fw-bold">Previous Records</div>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        @php(
                                            $previousRecords = array_filter($previous_records1, function ($rec) use ($branchId) {
                                                return $rec["branches_id"] === $branchId;
                                            })
                                        )
                                        @foreach ($previousRecords as $count)
                                            {{ $count["count"] }}
                                        @endforeach
                                        <span class="visually-hidden">records</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>