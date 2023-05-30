<x-dashboard-layout>
    <x-slot:title>
        Purchase Requests List
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Purchase Requests List <span class="badge bg-primary">' . Auth::user()->ppmp_year . '</span>'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    @if ($is_pr_enabled)
        <div class="mb-3">
            <span class="float-end small"># of records: <span class="badge text-bg-secondary">{{ count($pr_records) }}</span></span>
        </div>
        <div class="mb-3">
            <a href="{{ route('pr-form.show') }}" class="btn btn-outline-success"><em class="bi bi-plus-circle"></em> New Purchase Request</a>
        </div>
    @else
        <div class="d-flex align-items-center justify-content-center mb-5">
            <div class="fs-5 fw-bold fst-italic text-secondary">
                <div class="text-center fs-1"><em class="bi bi-exclamation-triangle"></em></div>
                Purchase request submissions is is disabled for <span class="badge bg-primary">{{ Auth::user()->ppmp_year }}</span>. Please get in touch with procurement office.
            </div>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-small table-bordered" id="pr-list-user">
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
                        <td>
                            @if ($pr->is_approve === 1)
                                <a class="btn btn-primary btn-sm" target="_blank" href="{{ route('pr-print.user', ["pr_id" => $pr->id]) }}"><em class="bi bi-printer-fill"></em></a>
                            @else
                                <button disabled class="btn btn-primary" type="button"><em class="bi bi-printer-fill"></em></button>
                            @endif
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
    {{-- <x-slot:additional_script>
        @include('layout/datatable', ['tableId' => 'pr-list-user'])
    </x-slot> --}}
</x-dashboard-layout>