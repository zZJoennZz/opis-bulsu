<x-dashboard-layout>
    <x-slot:title>
        {{-- {{ $item->item->bac_reso_item->quotation->brand_and_model_offered . " / " .
        $item->item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }} --}}
        </x-slot>

        @php
        $breadcrumb = [
        ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
        ['name' => 'Items', 'route' => 'transfered_items.all'],
        ['name' => 'ASD'],
        ]
        @endphp

        <x-breadcrumb :breadcrumb="$breadcrumb" />
        <div class="mb-3">
            ASDASDASDASDASDAS
        </div>
        {{-- <x-slot:additional_script>

            </x-slot> --}}
</x-dashboard-layout>