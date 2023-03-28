<x-dashboard-layout>
    <x-slot:title>
        {{ $aoc[0]->pr->pr_number }} Abstract of Canvass
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>'],
            ['name' => 'Abstract of Canvasses <span class="badge bg-primary">'. Auth::user()->ppmp_year .'</span>', 'route' => 'aoc.all'],
            ['name' => 'View ' . $aoc[0]->pr->pr_number . ' Abstract of Canvass'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    
    <div class="table-responsive">
        <table class="table table-sm table-bordered border-dark">
            <thead class="text-center">
                <tr>
                    <th rowspan="3">Item No.</th>
                    <th rowspan="3">NAME OF ARTICLES BEING REQUISITIONED</th>
                    <th rowspan="3">Unit</th>
                    <th rowspan="3">Qty.</th>
                    <th rowspan="3">Unit Price</th>
                    <th rowspan="3">Extended Amount</th>
                    <th colspan="{{ count($companies) * 3 }}">NAME OF BIDDERS/DEALERS</th>
                </tr>
                <tr>
                    @for ($i = 0; $i < count($companies); $i++)
                        <th colspan="3">{{ $companies[$i]->name }}</th>
                    @endfor
                </tr>
                <tr>
                    @for ($i = 0; $i < count($companies); $i++)
                        <th>Unit Price</th>
                        <th>Brand</th>
                        <th>Extended Amount</th>
                    @endfor
                </tr>
            </thead>

            <tbody>
                @php
                    $itemNo = 1;
                @endphp
                @foreach ($aoc[0]->pr->pr_items as $item)
                    <tr>
                        <td>{{ $itemNo }}</td>
                        @php
                            $itemNo += 1;
                        @endphp
                        <td>{{ $item->ppmp->item_detail->description }}</td>
                        <td>{{ $item->ppmp->item_detail->unit->uom }}</td>
                        @php
                            $qty = 0;
                            foreach($item->ppmp->milestones as $milestone) {
                                $qty += $milestone->milestone_value;
                            }
                        @endphp
                        <td>{{ $qty }}</td>
                        <td>{{ number_format($item->ppmp->item_detail->price_catalogue, 2) }}</td>
                        <td>{{ number_format($item->ppmp->item_detail->price_catalogue * $qty, 2) }}</td>
                        @for ($i = 0; $i < count($companies); $i++)
                            @foreach ($companies[$i]->quotations as $quote)
                                @php
                                    $ctr = 0;
                                @endphp
                                @foreach ($quote->items as $qitem)
                                    @if ($qitem->pr_item->id === $item->id)
                                        <td>
                                            {{ number_format($qitem->offered_unit_price, 2) }}
                                        </td>
                                        @php
                                            $ctr += 1;
                                        @endphp
                                    @endif
                                    @if ($qitem->pr_item->id === $item->id)
                                        <td>
                                            {{ $qitem->brand_and_model_offered }}
                                        </td>
                                        @php
                                            $ctr += 1;
                                        @endphp
                                    @endif
                                    @if ($qitem->pr_item->id === $item->id)
                                        <td>
                                            {{ number_format($qitem->offered_unit_price * $qty, 2) }}
                                        </td>
                                        @php
                                            $ctr += 1;
                                        @endphp
                                    @endif
                                @endforeach
                                @if ($ctr === 0)
                                    <td colspan="3">N/A</td>
                                @endif
                            @endforeach
                        @endfor
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <x-slot:additional_script>

    </x-slot>
</x-dashboard-layout>