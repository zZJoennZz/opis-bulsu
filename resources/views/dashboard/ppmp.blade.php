<x-dashboard-layout>
    <x-slot:title>
        Project Procurement Management Plan Request
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'PPMP <span class="badge bg-primary">' . Auth::user()->ppmp_year . '</span>']
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    <div class="table-responsive mb-3">
        <table class="table table-sm table-bordered border-dark caption-top" id="ppmp-request-table">
            <caption>Project Procurement Management Plan Request <span class="badge text-bg-primary">Year <strong>{{ Auth::user()->ppmp_year }}</strong></span></caption>
            <thead class="text-center">
                <tr>
                    <th rowspan="2">Revise</th>
                    <th rowspan="2" scope="col">Item Description</th>
                    <th rowspan="2" scope="col">Unit of Measurement</th>
                    <th rowspan="2" scope="col">Estimated Budget</th>
                    <th colspan="{{ count($ppmp_format) }}" scope="col">Schedule/Milestone of Activities</th>
                    <th rowspan="2" scope="col">Total Qty</th>
                    <th rowspan="2" scope="col">Price Catalogue</th>
                    <th rowspan="2" scope="col">Total Amount</th>
                    <th rowspan="2" scope="col">Remarks</th>
                </tr>
                <tr>
                    @foreach ($ppmp_format as $format)
                        <th id="{{ $format->id }}">{{ $format->name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php ($totalTotalAmount = 0)
                @foreach ($cart_items as $item)
                    @php ($totalAmount = 0)
                    @php ($totalQty = 0)
                    <tr>
                        <td>
                            @if (!is_null($item->pr_item))
                                <span class="badge bg-danger">PR Submitted</span>
                            @else
                                <a href="{{ route('get-ppmp-record.show', ['ppmp_id' => $item->id]) }}" class="btn btn-success btn-sm"><em class="bi bi-pencil-square"></em></a>
                            @endif
                        </td>
                        <td>
                            {{ $item->description }}
                            <div>
                                @if ($item->is_bo_approve === 1)
                                    <span class="badge text-bg-primary"><em class="bi bi-check-circle-fill"></em> Budget Office</span>
                                @endif
                                @if ($item->is_pr_approve === 1)
                                    <span class="badge text-bg-primary"><em class="bi bi-check-circle-fill"></em> Procurement Unit</span>
                                @endif
                            </div>
                        </td>
                        <td>{{ $item->uom }}</td>
                        <td>₱{{ number_format($item->estimated_budget, 2) }}</td>
                        @foreach ($milestones as $milestone)
                            @if ($milestone->pro_pro_man_plans_id === $item->id)
                                @php ($totalQty += $milestone->milestone_value)
                                <td>{{ $milestone->milestone_value }}</td>
                            @endif
                        @endforeach
                        <td>{{ $totalQty }}</td>
                        <td>₱{{ number_format($item->price_catalogue, 2) }}</td>
                        @php ($totalAmount = $totalQty * $item->price_catalogue)
                        <td>₱{{ number_format($totalAmount, 2) }}</td>
                        @php ($totalTotalAmount = floatval($totalTotalAmount) + floatval($totalAmount))
                        <td>{{ $item->remarks }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td colspan="{{ count($ppmp_format) + 4 }}" class="fs-3 text-uppercase text-end">
                        <strong>Total Amount</strong>
                    </td>
                    <td colspan="2" class="fs-3 text-uppercase text-start">
                        ₱{{ number_format($totalTotalAmount, 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <x-slot:additional_script>
        @include('layout/datatable', ["tableId" => "ppmp-request-table"])
    </x-slot>
</x-dashboard-layout>