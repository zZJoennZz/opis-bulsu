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
    
    <div class="mb-1">
        <strong>Purpose:</strong> {{ $aoc[0]->purpose }}
    </div>
    <div class="mb-3">
        <strong>ABC:</strong> ₱ {{ number_format($aoc[0]->abc, 2) }} (<span class="text-uppercase">{{ translateToWords($aoc[0]->abc) }}</span> )
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-bordered border-dark caption-top">
            <caption>PR NUMBER: <span class="badge bg-primary">{{ $aoc[0]->pr->pr_number }}</span></caption>
            <thead class="text-uppercase text-center align-middle">
                <tr>
                    <th scope="col" style="width: 50px;" rowspan="3">Item No.</th>
                    <th scope="col" rowspan="3">Name of Articles Being Requisitioned</th>
                    <th scope="col" style="width: 80px;" rowspan="3">Unit</th>
                    <th scope="col" style="width: 80px;" rowspan="3">Qty.</th>
                    <th scope="col" style="width: 130px;" rowspan="3">Unit Price</th>
                    <th scope="col" style="width: 130px;" rowspan="3">Extended Amount</th>
                    <th scope="col" colspan="{{ count($companies) * 3 }}">Name of the Bidders / Dealers</th>
                </tr>
                <tr>
                    @foreach ($companies as $c)
                        <th scope="col" colspan="3" class="text-primary">{{ $c->name }}</th>
                    @endforeach
                </tr>
                <tr>
                    @for ($i = 0; $i < count($companies); $i++)
                        <th scope="col" style="width: 70px; font-size: 12px">Unit Price</th>
                        <th scope="col" style="width: 70px; font-size: 12px">Brand</th>
                        <th scope="col" style="width: 100px; font-size: 12px">Extended Amount</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @php
                    $ctr = 1;
                @endphp
                @foreach ($aoc[0]->pr->pr_items as $item)
                    <tr>
                        <td>{{ $ctr }}</td>
                        <td>{{ $item->ppmp->item_detail->description }}</td>
                        <td>{{ $item->ppmp->item_detail->unit->uom }}</td>
                        {{-- COMPUTING THE QTY --}}
                        @php
                            $itemQty = 0;
                        @endphp
                        @foreach ($item->ppmp->milestones as $m)
                            @php
                                $itemQty += $m->milestone_value;
                            @endphp
                        @endforeach
                        <td>{{ $itemQty }}</td>
                        <td>₱ <div class="float-end">{{ number_format($item->ppmp->item_detail->price_catalogue, 2) }}</div></td>
                        <td>₱ <div class="float-end">{{ number_format($item->ppmp->item_detail->price_catalogue * $itemQty, 2) }}</div></td>
                        
                        {{-- LISTING COMPANY QUOTATIONS --}}
                        @foreach ($companies as $c)
                            @php
                                $itemsFound = 0;
                            @endphp
                            @foreach ($c->quotations as $q)
                                @foreach ($q->items as $i)
                                    @if ($i->pr_item->id === $item->id)
                                        <td>₱ <div class="float-end">{{ number_format($i->offered_unit_price, 2) }}</td>
                                        <td>{{ $i->brand_and_model_offered }}</td>
                                        <td>₱ <div class="float-end">{{ number_format($i->offered_unit_price * $itemQty, 2) }}</div></td>
                                        @php
                                            $itemsFound += 1;
                                        @endphp
                                    @endif
                                @endforeach
                            @endforeach
                            @if ($itemsFound === 0)
                                <td class="text-center" style="font-size: 11px;">N/A</td>
                                <td class="text-center" style="font-size: 11px;">N/A</td>
                                <td class="text-center" style="font-size: 11px;">N/A</td>
                            @endif
                        @endforeach

                        @php
                            $ctr += 1;
                            $itemQty = 0;
                        @endphp
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <x-slot:additional_script>

    </x-slot>
</x-dashboard-layout>