<x-dashboard-layout>
    <x-slot:title>
        {{ $endUser->first_name . ' ' . $endUser->middle_name . ' ' . $endUser->last_name }}'s Items
    </x-slot>

        @php
        $breadcrumb = [
        ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
        ['name' => 'Keepers', 'route' => 'end_users.all'],
        ['name' => $endUser->first_name . ' ' . $endUser->middle_name . ' ' . $endUser->last_name . '\'s'. ' Items'],
        ]
        @endphp

        <x-breadcrumb :breadcrumb="$breadcrumb" />
        
        <table class="w-100">
            <thead class="text-center">
                <tr>
                    <th class="border border-dark" rowspan="2" style="width: 10%;">Article</th>
                    <th class="border border-dark" rowspan="2" style="width: 30%;">Description</th>
                    <th class="border border-dark" rowspan="2" style="width: 10%;">Semi-expendable Property No.</th>
                    <th class="border border-dark" rowspan="2" style="width: 5%;">Unit of Measure</th>
                    <th class="border border-dark" rowspan="2" style="width: 10%;">Unit Value</th>
                    <th class="border border-dark" style="width: 5%;">Balance Per Card</th>
                    <th class="border border-dark" style="width: 5%;">On Hand Per Count</th>
                    <th class="border border-dark" colspan="2" style="width: 10%;">Shortage/Overage</th>
                    <th class="border border-dark" rowspan="2" style="width: 20%;">Remarks</th>
                </tr>
                <tr>
                    <td class="border border-dark">(Quantity)</td>
                    <td class="border border-dark">(Quantity)</td>
                    <td class="border border-dark">(Quantity)</td>
                    <td class="border border-dark">(Quantity)</td>
                </tr>
            </thead>
            <tbody>
                @if (count($eqCodesHighValue) > 0)
                    <tr>
                        <td colspan="2" class="border border-dark fw-bold">
                            Below P50,000 - HIGH VALUED ITEMS
                        </td>
                        <td class="border border-dark"></td>
                        <td class="border border-dark"></td>
                        <td class="border border-dark"></td>
                        <td class="border border-dark"></td>
                        <td class="border border-dark"></td>
                        <td class="border border-dark"></td>
                        <td class="border border-dark"></td>
                        <td class="border border-dark"></td>
                    </tr>
                    @php
                        $lastEqId = 0;
                    @endphp
                    @foreach ($eqCodesHighValue as $highValue)
                        @foreach ($highValue->items as $item)
                            @if ($item->properties[0]->serial_number !== "n/a")
                                @foreach ($item->properties as $property)
                                <tr>
                                    <td class="border border-dark">
                                        @if ($highValue->article === "SEMI_EXPENDABLE")
                                            <div>Semi-expendable</div>
                                        @endif
                                        @if ($lastEqId !== $highValue->id)
                                            {{ $highValue->description }}
                                            @php
                                                $lastEqId = $highValue->id;
                                            @endphp
                                        @endif
                                    </td>
                                    <td class="border border-dark">
                                        <div>{{ $item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}, {{ $item->bac_reso_item->quotation->brand_and_model_offered }} SN: {{ $property->serial_number }}</div>
                                        <div class="fw-bold">
                                            Date Acquired: {{ date('m-d-Y', strtotime($item->transaction->date_acquired)) }}
                                        </div>
                                    </td>
                                    <td class="border border-dark">
                                        {{ $item->property_number }}
                                    </td>
                                    <td class="border border-dark">
                                        {{ $item->bac_reso_item->quotation->pr_item->ppmp->item_detail->unit->uom }}
                                    </td>
                                    <td class="border border-dark">
                                        ₱ {{ number_format($item->bac_reso_item->quotation->offered_unit_price, 2) }}
                                    </td>
                                    <td class="border border-dark">1</td>
                                    <td class="border border-dark"></td>
                                    <td class="border border-dark"></td>
                                    <td class="border border-dark"></td>
                                    <td class="border border-dark"></td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="border border-dark">
                                        @if ($highValue->article === "SEMI_EXPENDABLE")
                                            <div>Semi-expendable</div>
                                        @endif
                                        @if ($lastEqId !== $highValue->id)
                                            {{ $highValue->description }}
                                            @php
                                                $lastEqId = $highValue->id;
                                            @endphp
                                        @endif
                                    </td>
                                    <td class="border border-dark">
                                        <div>{{ $item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}, {{ $item->bac_reso_item->quotation->brand_and_model_offered }}</div>
                                        <div class="fw-bold">
                                            Date Acquired: {{ date('m-d-Y', strtotime($item->transaction->date_acquired)) }}
                                        </div>
                                    </td>
                                    <td class="border border-dark">
                                        {{ $item->property_number }}
                                    </td>
                                    <td class="border border-dark">
                                        {{ $item->bac_reso_item->quotation->pr_item->ppmp->item_detail->unit->uom }}
                                    </td>
                                    <td class="border border-dark">
                                        ₱ {{ number_format($item->bac_reso_item->quotation->offered_unit_price, 2) }}
                                    </td>
                                    <td class="border border-dark">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="border border-dark"></td>
                                    <td class="border border-dark"></td>
                                    <td class="border border-dark"></td>
                                    <td class="border border-dark"></td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                @endif
                @if (count($eqCodesLowValue) > 0)
                    <tr>
                        <td colspan="2" class="border border-dark fw-bold">
                            Below P50,000 - LOW VALUED ITEMS
                        </td>
                        <td class="border border-dark"></td>
                        <td class="border border-dark"></td>
                        <td class="border border-dark"></td>
                        <td class="border border-dark"></td>
                        <td class="border border-dark"></td>
                        <td class="border border-dark"></td>
                        <td class="border border-dark"></td>
                        <td class="border border-dark"></td>
                    </tr>
                    @php
                        $lastEqId = 0;
                    @endphp
                    @foreach ($eqCodesLowValue as $lowValue)
                        @foreach ($lowValue->items as $item)
                            @if ($item->properties[0]->serial_number !== "n/a")
                                @foreach ($item->properties as $property)
                                <tr>
                                    <td class="border border-dark">
                                        @if ($lowValue->article === "SEMI_EXPENDABLE")
                                            <div>Semi-expendable</div>
                                        @endif
                                        @if ($lastEqId !== $lowValue->id)
                                            {{ $lowValue->description }}
                                            @php
                                                $lastEqId = $lowValue->id;
                                            @endphp
                                        @endif
                                    </td>
                                    <td class="border border-dark">
                                        <div>{{ $item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}, {{ $item->bac_reso_item->quotation->brand_and_model_offered }} SN: {{ $property->serial_number }}</div>
                                        <div class="fw-bold">
                                            Date Acquired: {{ date('m-d-Y', strtotime($item->transaction->date_acquired)) }}
                                        </div>
                                    </td>
                                    <td class="border border-dark">
                                        {{ $item->property_number }}
                                    </td>
                                    <td class="border border-dark">
                                        {{ $item->bac_reso_item->quotation->pr_item->ppmp->item_detail->unit->uom }}
                                    </td>
                                    <td class="border border-dark">
                                        ₱ {{ number_format($item->bac_reso_item->quotation->offered_unit_price, 2) }}
                                    </td>
                                    <td class="border border-dark">1</td>
                                    <td class="border border-dark"></td>
                                    <td class="border border-dark"></td>
                                    <td class="border border-dark"></td>
                                    <td class="border border-dark"></td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="border border-dark">
                                        @if ($lowValue->article === "SEMI_EXPENDABLE")
                                            <div>Semi-expendable</div>
                                        @endif
                                        @if ($lastEqId !== $lowValue->id)
                                            {{ $lowValue->description }}
                                            @php
                                                $lastEqId = $lowValue->id;
                                            @endphp
                                        @endif
                                    </td>
                                    <td class="border border-dark">
                                        <div>{{ $item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}, {{ $item->bac_reso_item->quotation->brand_and_model_offered }}</div>
                                        <div class="fw-bold">
                                            Date Acquired: {{ date('m-d-Y', strtotime($item->transaction->date_acquired)) }}
                                        </div>
                                    </td>
                                    <td class="border border-dark">
                                        {{ $item->property_number }}
                                    </td>
                                    <td class="border border-dark">
                                        {{ $item->bac_reso_item->quotation->pr_item->ppmp->item_detail->unit->uom }}
                                    </td>
                                    <td class="border border-dark">
                                        ₱ {{ number_format($item->bac_reso_item->quotation->offered_unit_price, 2) }}
                                    </td>
                                    <td class="border border-dark">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="border border-dark"></td>
                                    <td class="border border-dark"></td>
                                    <td class="border border-dark"></td>
                                    <td class="border border-dark"></td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                @endif
            </tbody>
        </table>
        
        <x-slot:additional_script>
        </x-slot>
</x-dashboard-layout>