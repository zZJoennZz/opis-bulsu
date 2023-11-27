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
                <caption>Select item to maintain or dispose</caption>
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
                            <td>{{ $property->item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}, {{ $property->item->bac_reso_item->quotation->brand_and_model_offered }}, S/N: {{ $property->serial_number ?? "n/a" }}</td>
                            <td>{{ number_format($property->item->bac_reso_item->quotation->offered_unit_price, 2) }}</td>
                            <td>{{ $property->current_owners[0]->end_user->first_name . ' ' . $property->current_owners[0]->end_user->middle_name . ' ' . $property->current_owners[0]->end_user->last_name }}</td>
                            <td>
                                {{-- <a class="btn btn-secondary btn-sm" href="{{ route('maintenance.select', ['id' => $property->id]) }}"><em class="bi bi-tools"></em></a> --}}
                                <button class="btn-sm btn-success btn" onclick="toggleFunction(this, {{ $property->id }})" data-action="add"><em class="bi bi-plus"></em></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="my-2 text-end">
            <form id="openSelectFormPage" action="{{ route('maintenance.select') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Continue to form</button>
            </form>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <table class="table table-sm border-dark caption-top" id="item-maintenance">
                <caption>Properties in Maintenance</caption>
                <thead>
                    <tr>
                        <th>RRSP Number</th>
                        <th>Returned/Requested By</th>
                        <th>Date of Maintenance</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($groupedMaintenanceProperties as $property)
                    <tr>
                        <td>{{ $property[0]->histories[0]->record_number }}</td>
                        <td>{{ $property[0]->current_owners[0]->end_user->first_name . ' ' . $property[0]->current_owners[0]->end_user->middle_name . ' ' . $property[0]->current_owners[0]->end_user->last_name }} / {{ $property[0]->current_owners[0]->end_user->branch->branch_name }}</td>
                        <td>{{ $property[0]->histories[0]->created_at }}</td>
                        <td>
                            <div><a class="btn btn-link btn-sm" href="{{ route('maintenance.print', ['rec_number' => $property[0]->histories[0]->record_number, 'mode' => 'rrppe']) }}" target="_blank"><em class="bi bi-printer-fill"></em> Request for Return of Property, Plant & Equipment</a></div>
                            <div><a class="btn btn-link btn-sm" href="{{ route('maintenance.print', ['rec_number' => $property[0]->histories[0]->record_number, 'mode' => 'rssp']) }}" target="_blank"><em class="bi bi-printer-fill"></em> Receipt of Returned Semi-Expendable Property</a></div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- <div class="row">
        <div class="col-12">
            <table class="table table-sm caption-top" id="item-disposal">
                <caption>Inventory and Inspection Report of Unserviceable Semi-Expendable Property Reports</caption>
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
                        @if ($property->histories[0]->type === "DISPOSE")
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
    </div> --}}

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
    <script>
        function addItem(idToAdd) {
            // Check if localStorage is supported by the browser
            if (typeof (Storage) !== "undefined") {
                // Get the current selectedItems from localStorage or an empty array if it doesn't exist
                var selectedItems = JSON.parse(localStorage.getItem('selectedItems')) || [];
    
                // Check if the ID already exists in the array
                if (!selectedItems.includes(idToAdd)) {
                    // Add the new item to the array
                    selectedItems.push(idToAdd);
    
                    // Save the updated array back to localStorage
                    localStorage.setItem('selectedItems', JSON.stringify(selectedItems));
    
                    // Optionally, you can perform any other actions or update the UI here
                    console.log("Item added to localStorage:", idToAdd);
                } else {
                    console.log("Item already exists in localStorage:", idToAdd);
                }
            } else {
                // If localStorage is not supported by the browser, handle the error accordingly
                console.error("LocalStorage is not supported by your browser. Use a different browser.");
            }
        }
    
        function removeItem(idToRemove) {
            // Check if localStorage is supported by the browser
            if (typeof (Storage) !== "undefined") {
                // Get the current selectedItems from localStorage or an empty array if it doesn't exist
                var selectedItems = JSON.parse(localStorage.getItem('selectedItems')) || [];

                // Use a loop to find the item and remove it
                for (var i = 0; i < selectedItems.length; i++) {
                    if (selectedItems[i] === idToRemove) {
                        // Remove the item from the array
                        selectedItems.splice(i, 1);

                        // Save the updated array back to localStorage
                        localStorage.setItem('selectedItems', JSON.stringify(selectedItems));

                        // Optionally, you can perform any other actions or update the UI here
                        console.log("Item removed from localStorage:", idToRemove);

                        // Break out of the loop once the item is found and removed
                        break;
                    }
                }
            } else {
                // If localStorage is not supported by the browser, handle the error accordingly
                console.error("LocalStorage is not supported by your browser. Use a different browser.");
            }
        }
    </script>
    <script>
        // Function to toggle between addItem and removeItem
        function toggleFunction(button, id) {
            var isAdd = button.getAttribute('data-action') === 'add';

            // Toggle between addItem and removeItem based on the current action
            if (isAdd) {
                addItem(id);
                button.setAttribute('data-action', 'remove');
                button.innerHTML = '<i class="bi bi-dash"></i>';
                button.classList.remove('btn-success');
                button.classList.add('btn-danger');
            } else {
                removeItem(id);
                button.setAttribute('data-action', 'add');
                button.innerHTML = '<i class="bi bi-plus"></i>';
                button.classList.add('btn-success');
                button.classList.remove('btn-danger');
            }
        }

        localStorage.removeItem('selectedItems');

        document.getElementById('openSelectFormPage').addEventListener('submit', function(e) {
            e = e || window.event;
            e.preventDefault();

            const selectedItems = localStorage.getItem('selectedItems');

            if (selectedItems && selectedItems.length > 0) {
                console.log("Selected Items:", selectedItems);
                var hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'selectedItems';
                hiddenInput.value = selectedItems;

                // Append the hidden input field to the form
                this.appendChild(hiddenInput);

                // Manually submit the form after updating the hidden input field
                this.submit();
            } else {
                alert('Select items to dispose/maintain.');
            }
        });
    </script>
    </x-slot>
</x-dashboard-layout>