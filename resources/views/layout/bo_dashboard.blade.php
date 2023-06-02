@php
    $noPpmpBranches = 0;
@endphp
<div class="modal fade" id="unsubmittedPPMPbo" tabindex="-1" aria-labelledby="unsubmittedPPMPbo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="unsubmittedPPMPbo"><em class="bi bi-asterisk"></em> Unsubmitted PPMP</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                <ul class="list-group">
                    @foreach($branches as $branch)
                        @if(count($branch->ppmp) === 0)
                        @php
                            $noPpmpBranches += 1;
                        @endphp
                        <li class="list-group-item">{{ $branch->branch_name }}</li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <button type="button" class="btn btn-sm btn-secondary me-3 float-end" data-bs-toggle="modal" data-bs-target="#unsubmittedPPMPbo">
            <span class="badge bg-light text-dark fw-bold">{{ $noPpmpBranches }}</span> Unsubmitted PPMP
        </button>
        <div class="fw-bold text-uppercase fs-4">Budget Office Dashboard</div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="text-muted small">List of campuses/offices</div>
    </div>
</div>
<div class="row">
    @foreach ($branches as $branch)
        <div class="col-sm-12 col-md-6">
            <div class="my-2">
                <div class="border border-primary rounded p-3">
                    <a href="{{ route('ppmp-activity-log.show', ['branch_id' => $branch->id]) }}" class="float-end btn btn-link btn-sm"><em class="bi bi-clock-history"></em> History Logs</a>
                    <div>
                        <span class="badge bg-primary">
                            @if ($branch->type === "CAMPUS")
                                <em class="bi bi-buildings-fill"></em>
                            @else
                                <em class="bi bi-building-fill"></em>
                            @endif
                            {{ $branch->type }}
                        </span>
                    </div>
                    <div class="text-primary fs-4 fw-bold mb-4">{{ $branch->branch_name }}</div>
                    <div class="row">
                        <div class="col-4">
                            @php
                                $isDisabled = true;
                                if (count($branch->ppmp->where('is_bo_approve', 0)->where('is_delete', 0)->where('is_draft', 0)) === 0) {
                                    $isDisabled = true;
                                } else {
                                    $isDisabled = false;
                                }
                            @endphp
                            <a href="{{ $isDisabled ? "#" : route('bo-new-ppmp-request.show', ['branch_id' => $branch->id]) }}" class="shadow h-100 w-100 rounded-4 p-4 text-center d-flex align-items-center justify-content-center flex-column position-relative text-decoration-none" style="cursor: pointer;">
                                <div class="mb-md-2 position-absolute top-0 {{ $isDisabled ? "bg-secondary" : "bg-danger" }} text-light p-2" style="width: 50px; height: 50px; border-radius: 100%; margin-top: -1rem;">
                                    <em class="bi bi-file-earmark-spreadsheet" style="font-size: 1.4rem;"></em>
                                </div>
                                <div class="mt-4 fs-6 fw-bold {{ $isDisabled ? "text-secondary" : "text-danger" }}">
                                    Pending
                                </div>
                                <div class="fs-2 fw-bold {{ $isDisabled ? "text-secondary" : "text-danger" }}">
                                    {{ $isDisabled ? "None" : "Review" }}
                                </div>
                            </a>
                        </div>
                        <div class="col-4">
                            @php
                                $isDisabled = true;
                                if (count($branch->ppmp->where('is_bo_approve', 1)->where('is_delete', 0)->where('is_draft', 0)) === 0) {
                                    $isDisabled = true;
                                } else {
                                    $isDisabled = false;
                                }
                            @endphp
                            <a href="{{ $isDisabled ? "#" : route('approved-ppmp-request.show', ['branch_id' => $branch->id]) }}" class="shadow h-100 w-100 rounded-4 p-4 text-center d-flex align-items-center justify-content-center flex-column position-relative text-decoration-none" style="cursor: pointer;">
                                <div class="mb-md-2 position-absolute top-0 {{ $isDisabled ? "bg-secondary" : "bg-primary" }} text-light p-2" style="width: 50px; height: 50px; border-radius: 100%; margin-top: -1rem;">
                                    <em class="bi bi-journal-check" style="font-size: 1.4rem;"></em>
                                </div>
                                <div class="mt-4 fs-6 fw-bold {{ $isDisabled ? "text-secondary" : "text-primary" }}">
                                    Approved
                                </div>
                                <div class="fs-2 fw-bold {{ $isDisabled ? "text-secondary" : "text-primary" }}">
                                    {{ $isDisabled ? "None" : "View" }}
                                </div>
                            </a>
                        </div>
                        <div class="col-4">
                            @php
                                $isDisabled = true;
                                if (count($branch->ppmp->where('year', '<>',getPpmpYear())->where('is_bo_approve', 1)->where('is_pr_approve', 1)->where('is_delete', 0)->where('is_draft', 0)) === 0) {
                                    $isDisabled = true;
                                } else {
                                    $isDisabled = false;
                                }
                            @endphp
                            <a href="{{ $isDisabled ? "#" : route('approved-ppmp-request.show', ['branch_id' => $branch->id]) }}" class="shadow h-100 w-100 rounded-4 p-4 text-center d-flex align-items-center justify-content-center flex-column position-relative text-decoration-none" style="cursor: pointer;">
                                <div class="mb-md-2 position-absolute top-0 {{ $isDisabled ? "bg-secondary" : "bg-primary" }} text-light p-2" style="width: 50px; height: 50px; border-radius: 100%; margin-top: -1rem;">
                                    <em class="bi bi-file-earmark-text" style="font-size: 1.4rem;"></em>
                                </div>
                                <div class="mt-4 fs-6 fw-bold {{ $isDisabled ? "text-secondary" : "text-primary" }}">
                                    All Records
                                </div>
                                <div class="fs-2 fw-bold {{ $isDisabled ? "text-secondary" : "text-primary" }}">
                                    {{ $isDisabled ? "None" : "View" }}
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
{{-- <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h2 class="h3">Budget Office Dashboard <span class="badge bg-primary">{{ Auth::user()->ppmp_year }}</span></h2>
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
                <div class="fs-2 fw-bold text-primary">
                    NEW
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
                <div class="fs-6 mt-2 text-uppercase fst-italic text-secondary">
                    No new submission yet
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
                <div class="fs-2 fw-bold text-primary">
                    APPROVED
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
                <div class="fs-6 fst-italic text-uppercase text-secondary">
                    Not approved yet
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
@endforeach --}}