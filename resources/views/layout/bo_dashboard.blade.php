<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h2 class="h3">Budget Office Dashboard</h2>
</div>
<div class="mb-3 table-responsive" style="max-height: 90vh;">
    <table id="bo-dashboard-table" class="table table-sm border-dark caption-top">
        <caption>Project Procure Management Plan - Budget Requests</caption>
        <thead>
            <tr>
                <th style="width: 10%">PPMP Year</th>
                <th style="width: 30%">End-User / Unit</th>
                <th style="width: 15%">Requested By</th>
                <th style="width: 40%">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ppmp_list as $ppmp)
                <tr>
                    <td class="fs-3 fw-bold"><span class="badge bg-primary">{{ Auth::user()->ppmp_year }}</span></td>
                    <td class="fs-3 fw-bold">{{ $ppmp->branch_name }}</td>
                    <td>{{ $ppmp->username }}</td>
                    <td>
                        <div class="row p-2">
                            <div class="col-lg-3 col-md-12">
                                @php($branchId = $ppmp->id)
                                @php(
                                    $newBudgetRequests = array_filter($new_budget_requests, function ($rec) use ($branchId) {
                                        return $rec["branches_id"] === $branchId;
                                    })
                                )
                                @foreach ($newBudgetRequests as $count)
                                    @if ($count["count"] > 0)
                                    <a href="{{ route('bo-new-ppmp-request.show', ['branch_id' => $ppmp->id]) }}" class="btn h-100 btn-primary position-relative" style="font-size: 1rem;">
                                        <em class="bi bi-cart" style="font-size: 2rem;"></em>
                                        <div class="fw-bold">New PPMP Budget Request</div>
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            {{ $count["count"] }}
                                            <span class="visually-hidden">new requests</span>
                                        </span>
                                    </a>
                                    @else
                                    <a class="btn opacity-50 h-100 btn-primary position-relative" style="font-size: 1rem;">
                                        <em class="bi bi-cart" style="font-size: 2rem;"></em>
                                        <div class="fw-bold">New PPMP Budget Request</div>
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            {{ $count["count"] }}
                                            <span class="visually-hidden">new requests</span>
                                        </span>
                                    </a>
                                    @endif
                                @endforeach
                            </div>
                            <div class="col-lg-3 col-md-12">
                                @php(
                                    $approvedBudgetRequests = array_filter($approved_budget_request, function ($rec) use ($branchId) {
                                        return $rec["branches_id"] === $branchId;
                                    })
                                )
                                @foreach ($approvedBudgetRequests as $count)
                                    @if ($count["count"] > 0)
                                        <a href="{{ route('approved-ppmp-request.show', ['branch_id' => $ppmp->id]) }}" class="btn h-100 btn-success position-relative" style="font-size: 1rem;">
                                            <em class="bi bi-cart-check" style="font-size: 2rem;"></em>
                                            <div class="fw-bold">Approved PPMP Budget Request</div>
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                {{ $count["count"] }}
                                                <span class="visually-hidden">approved requests</span>
                                            </span>
                                        </a>
                                    @else
                                        <a class="btn h-100 opacity-50 btn-success position-relative" style="font-size: 1rem;">
                                            <em class="bi bi-cart-check" style="font-size: 2rem;"></em>
                                            <div class="fw-bold">Approved PPMP Budget Request</div>
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                {{ $count["count"] }}
                                                <span class="visually-hidden">approved requests</span>
                                            </span>
                                        </a>
                                    @endif
                                @endforeach
                                
                            </div>
                            <div class="col-lg-3 col-md-12">
                                <button class="btn h-100 btn-secondary position-relative" style="font-size: 1rem;">
                                    <em class="bi bi-file-earmark-text" style="font-size: 2rem;"></em>
                                    <div class="fw-bold">PPMP Historical Records</div>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        @php(
                                            $previousRecords = array_filter($previous_records, function ($rec) use ($branchId) {
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
                            <div class="col-lg-3 col-md-12">
                                <a href="{{ route('ppmp-activity-log.show', ['branch_id' => $branchId]) }}" class="btn h-100 btn-secondary position-relative" style="font-size: 1rem;">
                                    <em class="bi bi-clock-history" style="font-size: 2rem;"></em>
                                    <div class="fw-bold">Previous PPMP History Logs</div>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        @php(
                                            $ppmpLogs = array_filter($ppmp_logs, function ($rec) use ($branchId) {
                                                return $rec["branches_id"] === $branchId;
                                            })
                                        )
                                        @foreach ($ppmpLogs as $count)
                                            {{ $count["count"] }}
                                        @endforeach
                                        <span class="visually-hidden">previous ppmp</span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>