<x-dashboard-layout>
    <x-slot:title>
        Select maintenance form
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Item Maintenance', 'route' => 'maintenance.index'],
            ['name' => 'Form Type'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    
    <div class="row mb-3">
        <div class="col-12">
            <h1 class="h5 text-uppercase text-secondary">Form Type</h1>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-sm table-bordered caption-top">
                    <caption>Selected items</caption>
                    <thead>
                        <tr>
                            <th>Date Acquired</th>
                            <th>Description</th>
                            <th>Unit</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $groupedItems = [];

                            foreach ($selectedItems as $item) {
                                $itemId = $item->item->id;
                                $currentOwner = $item->current_owners[0]->supply_end_users_id;

                                $key = $itemId . '_' . $currentOwner;

                                if (!isset($groupedItems[$key])) {
                                    $groupedItems[$key] = [
                                        'items' => [],
                                        'qty' => 0,
                                        'itemId' => $itemId,
                                        'currentOwner' => $currentOwner,
                                    ];
                                }

                                $groupedItems[$key]['items'][] = $item;
                                $groupedItems[$key]['qty']++;
                            }
                        @endphp
                        @foreach ($groupedItems as $group)
                            @php
                                $item = $group['items'][0];
                                $qty = $group['qty'];
                                $itemId = $group['itemId'];
                                $currentOwner = $group['currentOwner'];
                            @endphp
                            <tr>
                                <td>{{ $item->item->transaction->date_acquired }}</td>
                                <td>{{ $item->item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description . ', ' . $item->item->bac_reso_item->quotation->brand_and_model_offered }}</td>
                                <td>{{ number_format($item->item->bac_reso_item->quotation->offered_unit_price, 2) }}</td>
                                <td>{{ $item->item->bac_reso_item->quotation->pr_item->ppmp->item_detail->unit->uom }}</td>
                                <td>{{ $qty }}</td>
                                <td>{{ number_format($item->item->bac_reso_item->quotation->offered_unit_price * $qty, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row text-center">
        <div class="col-sm-12 col-md-6">
            <a class="btn btn-primary btn-lg w-100" href="{{ route('maintenance.form') }}"><em class="bi bi-tools"></em> Maintenance</a>
        </div>
        <div class="col-sm-12 col-md-6">
            <a class="btn btn-secondary btn-lg w-100" href="{{ route('disposal.form') }}"><em class="bi bi-trash-fill"></em> Disposal</a>
        </div>
    </div>
    
    <x-slot:additional_script>

    </x-slot>
</x-dashboard-layout>