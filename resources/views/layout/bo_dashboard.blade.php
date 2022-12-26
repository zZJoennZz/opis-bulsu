<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h2 class="h3">Budget Office Dashboard</h2>
</div>
@foreach ($ppmp_list as $ppmp)
<div class="row border-bottom rounded-5 p-3 mb-3">
    <div class="col-12 col-lg-6 text-start p-3">
        <div class="fs-3"><span class="badge bg-primary">{{ Auth::user()->ppmp_year }}</span></div>
        <div class="fs-1 mb-1 fw-bold">{{$ppmp->branch_name}}</div>
        <div class="fst-italic text-secondary mb-3"><span class="fw-bold">Requested by:</span> {{ $ppmp->username }}</div>
        <div>
            <a href="{{ route('ppmp-activity-log.show', ['branch_id' => $ppmp->id]) }}" class="btn btn-secondary btn-sm"><em class="bi bi-clock-history"></em> PPMP History Logs</a>
        </div>
    </div>
    <div class="col-12 col-lg-2 mb-4">
        @php($branchId = $ppmp->id)
        @php(
            $newBudgetRequests = array_filter($new_budget_requests, function ($rec) use ($branchId) {
                return $rec["branches_id"] === $branchId;
            })
        )
        @foreach ($newBudgetRequests as $count)
            @if ($count["count"] > 0)
            <a href="{{ route('bo-new-ppmp-request.show', ['branch_id' => $ppmp->id]) }}" class="ppmpCard shadow h-100 w-100 rounded-4 p-4 text-center d-flex align-items-center justify-content-center flex-column position-relative text-decoration-none" style="cursor: pointer;">
                <div class="mb-md-2 position-absolute top-0 bg-primary text-light p-2" style="width: 50px; height: 50px; border-radius: 100%;">
                    <em class="bi bi-file-earmark-spreadsheet" style="font-size: 1.4rem;"></em>
                </div>
                <div class="mt-4 fs-6 fw-bold text-primary">
                    New PPMP Budget Request
                </div>
                <div class="fs-1 fw-bold text-primary">
                    {{ $count["count"] }}
                </div>
            </a>
            @else
            <a class="shadow h-100 w-100 rounded-4 p-4 text-center d-flex align-items-center justify-content-center flex-column position-relative text-decoration-none" style="cursor: pointer;">
                <div class="mb-md-2 position-absolute top-0 bg-secondary text-light p-2" style="width: 50px; height: 50px; border-radius: 100%;">
                    <em class="bi bi-file-earmark-spreadsheet" style="font-size: 1.4rem;"></em>
                </div>
                <div class="mt-4 fs-6 fw-bold text-secondary">
                    New PPMP Budget Request
                </div>
                <div class="fs-1 fw-bold text-secondary">
                    {{ $count["count"] }}
                </div>
            </a>
            @endif
        @endforeach
    </div>
    <div class="col-12 col-lg-2 mb-4">
        @php($branchId = $ppmp->id)
        @php(
            $approvedBudgetRequests = array_filter($approved_budget_request, function ($rec) use ($branchId) {
                return $rec["branches_id"] === $branchId;
            })
        )
        @foreach ($approvedBudgetRequests as $count)
            @if ($count["count"] > 0)
            <a href="{{ route('approved-ppmp-request.show', ['branch_id' => $ppmp->id]) }}" class="ppmpCard shadow h-100 w-100 rounded-4 p-4 text-center d-flex align-items-center justify-content-center flex-column position-relative text-decoration-none" style="cursor: pointer;">
                <div class="mb-md-2 position-absolute top-0 bg-primary text-light p-2" style="width: 50px; height: 50px; border-radius: 100%;">
                    <em class="bi bi-journal-check" style="font-size: 1.4rem;"></em>
                </div>
                <div class="mt-4 fs-6 fw-bold text-primary">
                    Approved PPMP Budget Request
                </div>
                <div class="fs-1 fw-bold text-primary">
                    {{ $count["count"] }}
                </div>
            </a>
            @else
            <a class="shadow h-100 w-100 rounded-4 p-4 text-center d-flex align-items-center justify-content-center flex-column position-relative text-decoration-none" style="cursor: pointer;">
                <div class="mb-md-2 position-absolute top-0 bg-secondary text-light p-2" style="width: 50px; height: 50px; border-radius: 100%;">
                    <em class="bi bi-journal-check" style="font-size: 1.4rem;"></em>
                </div>
                <div class="mt-4 fs-6 fw-bold text-secondary">
                    Approved PPMP Budget Request
                </div>
                <div class="fs-1 fw-bold text-secondary">
                    {{ $count["count"] }}
                </div>
            </a>
            @endif
        @endforeach
    </div>
    <div class="col-12 col-lg-2 mb-4">
        <a class="shadow h-100 w-100 rounded-4 p-4 text-center d-flex align-items-center justify-content-center flex-column position-relative text-decoration-none" style="cursor: pointer;">
            <div class="mb-md-2 position-absolute top-0 bg-secondary text-light p-2" style="width: 50px; height: 50px; border-radius: 100%;">
                <em class="bi bi-file-earmark-text" style="font-size: 1.4rem;"></em>
            </div>
            <div class="mt-4 fs-6 fw-bold text-secondary">
                PPMP Historical Records
            </div>
            <div class="fs-1 fw-bold text-secondary">
                N/A
            </div>
        </a>
    </div>
</div>
@endforeach
{{-- <div class="mb-3 table-responsive" style="max-height: 90vh;">
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
</div> --}}