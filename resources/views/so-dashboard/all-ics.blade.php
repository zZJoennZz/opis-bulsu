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
                    <th></th>
                    <th>Item</th>
                    <th>Available Unit/s</th>
                    <th>Unit Cost</th>
                    <th>College/Office</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ics as $i)
                    @foreach ($i->items as $item)
                        <tr>
                            <td><button class="btn btn-sm btn-secondary" type="button">.</button></td>
                            <td>{{ $item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}</td>
                            <td>
                                {{ $item->properties }}
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