@include('layout/header', ['title' => 'Purchase Requests List | OPIS - BulSU e-PROCUREMENT'])
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
                                ['name' => 'Purchase Requests List <span class="badge bg-primary">' . Auth::user()->ppmp_year . '</span>']
                            ]
                        ]
                        )
                        @if ($is_pr_enabled)
                            <div class="mb-3">
                                <span class="float-end small"># of records: <span class="badge text-bg-secondary">{{ count($pr_records) }}</span></span>
                            </div>
                            <div class="mb-3">
                                <a href="{{ route('pr-form.show') }}" class="btn btn-outline-success"><em class="bi bi-plus-circle"></em> New Purchase Request</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-small table-bordered border-dark">
                                    <caption>Purchase Requests for the Year <span class="badge bg-primary">{{ Auth::user()->ppmp_year }}</span></caption>
                                    <thead>
                                        <tr>
                                            <th>PR #</th>
                                            <th>Entity Name</th>
                                            <th>Requested By</th>
                                            <th>Date</th>
                                            <th>Item Description</th>
                                            <th>Quantity</th>
                                            <th>Estimated Budget</th>
                                            <th>Total Cost</th>
                                            <th>Fund Cluster</th>
                                            <th>Responsibility Center</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pr_records as $pr)
                                            <tr>
                                                <td>{{ $pr->pr_number }} <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample{{$pr->id}}" aria-expanded="false" aria-controls="collapseExample">
                                                    Button with data-bs-target
                                                  </button></td>
                                                <td>{{ $pr->branch->branch_name }}</td>
                                                <td>{{ $pr->requester->profile->first_name }} {{ $pr->requester->profile->last_name }}</td>
                                                <td>{{ date("m-d-Y", strtotime($pr->created_at)) }}</td>
                                                <td colspan="6"></td>
                                                <td>
                                                    <button class="btn btn-primary" type="button"><em class="bi bi-check2-circle"></em> Approve</button>
                                                </td>
                                                <td></td>
                                            </tr>
                                            @foreach ($pr->pr_items as $item)
                                                <tr class="collapse" id="collapseExample{{$pr->id}}">
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
                                                    <td>{{ number_format($item->ppmp->estimated_budget, 2) }}</td>
                                                    <td>{{ number_format($item->ppmp->estimated_budget * $total_qty, 2) }}</td>
                                                    <td>{{ $item->ppmp->source_of_fund->source_of_fund }}</td>
                                                    <td></td>
                                                    <td colspan="2"></td>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="d-flex align-items-center justify-content-center" style="min-height: 50vh;">
                                <div class="fs-5 fw-bold fst-italic text-secondary">
                                    <div class="text-center fs-1"><em class="bi bi-exclamation-triangle"></em></div>
                                    Purchase request submissions is not yet enabled for the year <span class="badge bg-primary">{{ Auth::user()->ppmp_year }}</span>. Please get in touch with procurement office.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
@include('layout/footer')