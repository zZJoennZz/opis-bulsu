<div class="d-flex flex-column justify-content-between flex-wrap flex-md-nowrap align-items-start pt-3 pb-2 mb-3 border-bottom">
    <h2 class="h3">Procurement Dashboard <span class="badge bg-primary">{{ Auth::user()->ppmp_year }}</span></h2>
</div>
<div class="row">
    <h3 class="fs-5 text-secondary">Project Procure Management Plan</h3>
</div>
@foreach ($ppmp_list1 as $ppmp)
<div class="row border-bottom rounded-5 p-3 mb-3">
    <div class="col-12 col-lg-6 text-start p-3">
        <div class="fs-3"><span class="badge bg-primary">{{ Auth::user()->ppmp_year }}</span></div>
        <div class="fs-1 mb-1 fw-bold">{{$ppmp->branch_name}}</div>
        <div class="fst-italic text-secondary"><span class="fw-bold">Requested by:</span> {{ $ppmp->username }}</div>
    </div>
    <div class="col-12 col-lg-2 mb-4">
        @php($branchId = $ppmp->id)
        @php(
            $newBudgetRequests = array_filter($new_budget_requests1, function ($rec) use ($branchId) {
                return $rec["branches_id"] === $branchId;
            })
        )
        @foreach ($newBudgetRequests as $count)
            @if ($count["count"] > 0)
            <a href="{{ route('po-ppmp-approval.show', ['branch_id' => $ppmp->id]) }}" class="ppmpCard shadow h-100 w-100 rounded-4 p-4 text-center d-flex align-items-center justify-content-center flex-column position-relative text-decoration-none" style="cursor: pointer;">
                <div class="mb-md-2 position-absolute top-0 bg-primary text-light p-2" style="width: 50px; height: 50px; border-radius: 100%;">
                    <em class="bi bi-file-earmark-spreadsheet" style="font-size: 1.4rem;"></em>
                </div>
                <div class="mt-4 fs-6 fw-bold text-primary">
                    New PPMP Approval
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
                    New PPMP Approval
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
            $approvedBudgetRequests = array_filter($approved_budget_request1, function ($rec) use ($branchId) {
                return $rec["branches_id"] === $branchId;
            })
        )
        @foreach ($approvedBudgetRequests as $count)
            @if ($count["count"] > 0)
            <a href="{{ route('po-approved-ppmp.show', ['branch_id' => $ppmp->id]) }}" class="ppmpCard shadow h-100 w-100 rounded-4 p-4 text-center d-flex align-items-center justify-content-center flex-column position-relative text-decoration-none" style="cursor: pointer;">
                <div class="mb-md-2 position-absolute top-0 bg-primary text-light p-2" style="width: 50px; height: 50px; border-radius: 100%;">
                    <em class="bi bi-journal-check" style="font-size: 1.4rem;"></em>
                </div>
                <div class="mt-4 fs-6 fw-bold text-primary">
                    Approved PPMP
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
                    Approved PPMP
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
            $previousRecords = array_filter($previous_records1, function ($rec) use ($branchId) {
                return $rec["branches_id"] === $branchId;
            })
        )
        @foreach ($previousRecords as $count)
            <a href="{{ route('po-ppmp-approval.show', ['branch_id' => $ppmp->id]) }}" class="ppmpCard shadow h-100 w-100 rounded-4 p-4 text-center d-flex align-items-center justify-content-center flex-column position-relative text-decoration-none" style="cursor: pointer;">
                <div class="mb-md-2 position-absolute top-0 bg-primary text-light p-2" style="width: 50px; height: 50px; border-radius: 100%;">
                    <em class="bi bi-file-earmark-text" style="font-size: 1.4rem;"></em>
                </div>
                <div class="mt-4 fs-6 fw-bold text-primary">
                    Previous Records
                </div>
                <div class="fs-1 fw-bold text-primary">
                    {{ $count["count"] }}
                </div>
            </a>
        @endforeach
    </div>
</div>
@endforeach
{{-- 
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
</div> --}}