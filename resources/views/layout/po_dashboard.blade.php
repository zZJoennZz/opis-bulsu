<div class="justify-content-between flex-wrap flex-md-nowrap align-items-start pt-3 pb-3 mb-3 border-bottom">
    <div class="float-lg-end">
        <button type="button" class="btn btn-warning me-3" data-bs-toggle="modal" data-bs-target="#unsubmittedPPMP">
            <em class="bi bi-asterisk"></em> Unsubmitted PPMP
        </button>
        <span class="text-secondary">
            # of submissions:
        </span>
        <span class="badge bg-secondary">
            {{ count($ppmp_records_count) }}
        </span>
    </div>
    <span class="h3">Project Procure Management Plan - Dashboard <span class="badge bg-primary">{{ Auth::user()->ppmp_year }}</span></span>
</div>
<div class="modal fade" id="unsubmittedPPMP" tabindex="-1" aria-labelledby="unsubmittedPPMP" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="unsubmittedPPMP"><em class="bi bi-asterisk"></em> Unsubmitted PPMP</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                <ul class="list-group">
                    @foreach($all_branches as $branch)
                        @if(!isset($branch->ppmp[0]))
                        <li class="list-group-item">{{ $branch->branch_name }}</li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@foreach($all_branches as $branch)
<div class="row border-bottom rounded-5 p-3 mb-3">
    <div class="col-12 col-lg-6 text-start p-3">
        <div class="fs-3"><span class="badge bg-primary">{{ Auth::user()->ppmp_year }}</span></div>
        <div class="fs-1 mb-1 fw-bold">{{$branch->branch_name}}</div>
        <div class="fst-italic text-secondary"><span class="fw-bold">Requested by:</span>
            @if(isset($branch->ppmp[0]) && ($branch->ppmp[0]->is_draft === 0))
            {{$branch->ppmp[0]->user_profile->first_name}} {{$branch->ppmp[0]->user_profile->last_name}}
            @else
            n/a
            @endif
        </div>
    </div>
    <div class="col-12 col-lg-2 mb-4">
        @php (
            $count = count($branch->ppmp->where('year', '=', Auth::user()->ppmp_year)->where('is_draft', '=', 0)->where('is_bo_approve', '=', 1)->where('is_pr_approve', '=', 0))
        )
        <a @if($count > 0) href="{{ route('po-ppmp-approval.show', ['branch_id' => $branch->id]) }}" @endif class="@if($count > 0) ppmpCard @endif shadow h-100 w-100 rounded-4 p-4 text-center d-flex align-items-center justify-content-center flex-column position-relative text-decoration-none" style="cursor: pointer;">
            <div class="mb-md-2 position-absolute top-0 @if($count > 0) bg-primary @else bg-secondary @endif text-light p-2" style="width: 50px; height: 50px; border-radius: 100%;">
                <em class="bi bi-file-earmark-spreadsheet" style="font-size: 1.4rem;"></em>
            </div>
            <div class="mt-4 fs-6 fw-bold @if($count > 0) text-primary @else text-secondary @endif">
                New PPMP Approval
            </div>
            <div class="fs-2 fw-bold @if($count > 0) text-primary @else text-secondary @endif">
                @if($count > 0  ) REVIEW @else N/A @endif
            </div>
        </a>
    </div>
    <div class="col-12 col-lg-2 mb-4">
        @php (
            $count = count($branch->ppmp->where('year', '=', Auth::user()->ppmp_year)->where('is_draft', '=', 0)->where('is_bo_approve', '=', 1)->where('is_pr_approve', '=', 1))
        )
        <a @if($count > 0) href="{{ route('po-approved-ppmp.show', ['branch_id' => $branch->id]) }}" @endif class="@if($count > 0) ppmpCard @endif shadow h-100 w-100 rounded-4 p-4 text-center d-flex align-items-center justify-content-center flex-column position-relative text-decoration-none" style="cursor: pointer;">
            <div class="mb-md-2 position-absolute top-0 @if($count > 0) bg-primary @else bg-secondary @endif text-light p-2" style="width: 50px; height: 50px; border-radius: 100%;">
                <em class="bi bi-journal-check" style="font-size: 1.4rem;"></em>
            </div>
            <div class="mt-4 fs-6 fw-bold @if($count > 0) text-primary @else text-secondary @endif">
                Approved PPMP
            </div>
            <div class="fs-2 fw-bold @if($count > 0) text-primary @else text-secondary @endif">
                @if($count > 0  ) APPROVED @else N/A @endif
            </div>
        </a>
    </div>
    <div class="col-12 col-lg-2 mb-4">
        @php (
            $count = count($branch->ppmp->where('year', '<>', Auth::user()->ppmp_year)->where('is_draft', '=', 0)->where('is_bo_approve', '=', 1)->where('is_pr_approve', '=', 1)->groupBy('year'))
        )
        <a @if($count > 0) href="{{ route('previous-ppmp.show', ["branch_id" => $branch->id]) }}" @endif class="@if($count > 0) ppmpCard @endif shadow h-100 w-100 rounded-4 p-4 text-center d-flex align-items-center justify-content-center flex-column position-relative text-decoration-none" style="cursor: pointer;">
            <div class="mb-md-2 position-absolute top-0 @if($count > 0) bg-primary @else bg-secondary @endif text-light p-2" style="width: 50px; height: 50px; border-radius: 100%;">
                <em class="bi bi-file-earmark-text" style="font-size: 1.4rem;"></em>
            </div>
            <div class="mt-4 fs-6 fw-bold @if($count > 0) text-primary @else text-secondary @endif">
                Previous Records
            </div>
            <div class="fs-2 fw-bold @if($count > 0) text-primary @else text-secondary @endif">
                {{ $count }}
            </div>
        </a>
    </div>
</div>
@endforeach

{{-- @foreach ($ppmp_list1 as $ppmp)
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
                    New PPMP Approval
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
                    Approved PPMP
                </div>
                <div class="fs-6 fst-italic text-uppercase text-secondary">
                    Not approved yet
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
@endforeach --}}
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