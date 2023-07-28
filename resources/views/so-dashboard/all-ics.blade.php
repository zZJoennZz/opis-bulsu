<x-dashboard-layout>
    <x-slot:title>
        All Inventory Custodian Slip Records
        </x-slot>

        @php
        $breadcrumb = [
        ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
        ['name' => 'All ICS Records'],
        ]
        @endphp

        <x-breadcrumb :breadcrumb="$breadcrumb" />
        <div class="table-responsive">
            <table class="table table-sm table-hover border-dark caption-top" id="all-ics-items">
                <caption>All ICS Items</caption>
                <thead class="small">
                    <tr>
                        <th>Transfer</th>
                        <th style="width: 40%;">Item</th>
                        <th>Available Unit/s</th>
                        <th>Unit Cost</th>
                        <th style="width: 30%;">College/Office</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ics as $i)
                    @foreach ($i->items as $item)
                    @php
                    $availableUnits = 0;
                    @endphp
                    @foreach ($item->properties as $property)
                    @php
                    if (count($property->transfers) === 0){
                    $availableUnits += 1;
                    }
                    @endphp
                    @endforeach
                    <tr>
                        <td>
                            @if ($availableUnits > 0)
                            <a class="btn btn-sm btn-secondary" href="{{ route('transfer_ics.get') }}/{{ $item->id }}"><em
                                    class="bi bi-arrow-right-short"></em></a>
                            @else
                            <button class="btn btn-sm btn-secondary" disabled type="button"><em class="bi bi-arrow-right-short"></em></button>
                            @endif
                        </td>
                        <td>{{ $item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}</td>
                        <td>
                            {{ $availableUnits }}
                        </td>
                        <td>₱ {{ number_format($item->unit_price, 2) }}</td>
                        <td>{{ $i->branch->branch_name }}</td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
        <x-slot:additional_script>
            @include('layout/datatable', ['tableId' => 'all-ics-items'])
            </x-slot>
</x-dashboard-layout>