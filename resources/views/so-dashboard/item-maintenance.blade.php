<x-dashboard-layout>
    <x-slot:title>
        Item Maintenance
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Item Maintenance'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />

    <div class="row mb-5">
        <div class="col-12">
            <div class="text-uppercase fw-bold fs-4 text-secondary">Available Properties</div>
            <table class="table table-sm" id="available-properties">
                <thead>
                    <tr>
                        <th style="width: 15%;">Date Acquired</th>
                        <th style="width: 35%;">Description</th>
                        <th style="width: 20%;">Amount</th>
                        <th style="width: 25%;">Current Keeper</th>
                        <th style="width: 5%;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($availableProperties as $property)
                        <tr>
                            <td>{{ $property->item->transaction->date_acquired }}</td>
                            <td>{{ $property->item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}, {{ $property->item->bac_reso_item->quotation->brand_and_model_offered }}</td>
                            <td>{{ number_format($property->item->bac_reso_item->quotation->offered_unit_price, 2) }}</td>
                            <td>{{ $property->current_owners[0]->end_user->first_name . ' ' . $property->current_owners[0]->end_user->middle_name . ' ' . $property->current_owners[0]->end_user->last_name }}</td>
                            <td><button class="btn btn-secondary btn-sm"><em class="bi bi-tools"></em></button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <div class="text-uppercase fw-bold fs-4 text-secondary">Properties in Maintenance</div>

            <table class="table table-sm" id="item-maintenance">
                <thead>
                    <tr>
                        <th>Date Acquired</th>
                        <th>Item Description</th>
                        <th>Current Keeper</th>
                        <th>Date of Maintenance</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($maintenanceProperties as $property)
                        @if ($property->histories[0]->type === "MAINTENANCE")
                        <tr>
                            <td>{{ $property->item->transaction->date_acquired }}</td>
                            <td>{{ $property->item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description . ', ' . $property->item->bac_reso_item->quotation->brand_and_model_offered }}</td>
                            <td>{{ $property->current_owners[0]->end_user->first_name . ' ' . $property->current_owners[0]->end_user->middle_name . ' ' . $property->current_owners[0]->end_user->last_name }}</td>
                            <td>{{ $property->histories[0]->created_at }}</td>
                            <td><a class="btn btn-secondary btn-sm" href="{{ route('maintenance.print', ['id' => $property->histories[0]->id]) }}" target="_blank"><em class="bi bi-printer-fill"></em></a></td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="text-uppercase fw-bold fs-4 text-secondary">Disposed Properties</div>
            <table class="table table-sm" id="item-disposal">
                <thead>
                    <tr>
                        <th>Date Acquired</th>
                        <th>Item Description</th>
                        <th>Current Keeper</th>
                        <th>Date of Disposal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($maintenanceProperties as $property)
                        @if ($property->histories[0]->type === "DISPOSAL")
                        <tr>
                            <td>{{ $property->item->transaction->date_acquired }}</td>
                            <td>{{ $property->item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description . ', ' . $property->item->bac_reso_item->quotation->brand_and_model_offered }}</td>
                            <td>{{ $property->current_owners[0]->end_user->first_name . ' ' . $property->current_owners[0]->end_user->middle_name . ' ' . $property->current_owners[0]->end_user->last_name }}</td>
                            <td>{{ $property->histories[0]->created_at }}</td>
                            <td><button class="btn btn-secondary btn-sm"><em class="bi bi-printer-fill"></em></button></td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <x-slot:additional_script>
    @include('layout/datatable', ["tableId" => "available-properties"])
    <script defer>
        $(document).ready(function() {
            $('#item-maintenance').DataTable(
                {
                "autoWidth": false,
            }
            );
        });
    </script>
    <script defer>
        $(document).ready(function() {
            $('#item-disposal').DataTable(
                {
                "autoWidth": false,
            }
            );
        });
    </script>
    </x-slot>
</x-dashboard-layout>