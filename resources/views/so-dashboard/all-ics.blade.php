<x-dashboard-layout>
    <x-slot:title>
        All Inventory Custodian Slips Records
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
            <thead>
                <tr>
                    <th></th>
                    <th>Item</th>
                    <th>Qty / Unit</th>
                    <th>Unit Cost</th>
                    <th>College/Office</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ics as $i)
                    @foreach ($i->items as $item)
                    @php
                        $totalQty = $item->quantity;

                        foreach ($item->transfers as $transfer) {
                            $totalQty -= $transfer->quantity;
                        }
                    @endphp
                    <tr>
                        <td><button type="button" onclick="window.location.href='{{ route('prepare-transfer.show') }}/{{ $item->id }}'" class="btn btn-sm btn-secondary" @if($totalQty === 0) disabled @endif><em class="bi bi-arrow-left-right"></em></button></td>
                        <td>
                            {{ $item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}
                            <div class="small text-muted">
                                <span class="badge bg-secondary">{{ $i->number }}</span>
                            </div>
                        </td>
                        <td>
                            {{ $totalQty }} / {{ $item->bac_reso_item->quotation->pr_item->ppmp->item_detail->unit->uom }}
                        </td>
                        <td>₱ {{ number_format($item->bac_reso_item->quotation->offered_unit_price, 2) }}</td>
                        <td>
                            @foreach ($i->receivers as $receiver)
                            {{ $receiver->end_user->branch->branch_name }}
                            @endforeach
                        </td>
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