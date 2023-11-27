<x-dashboard-layout>
    <x-slot:title>
        Edit Property Information
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Inventory and Inspection Report of Unserviceable Semi-Expendable Property', 'route' => 'iirup.index'],
            ['name' => 'Edit Property Information'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />

    <form action="{{ route('property.update') }}" method="post">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6 col-sm-12 mb-3">
                <label for="serial_number" class="form-label">Serial Number</label>
                <input type="text" class="form-control" id="serial_number" name="serial_number" value="{{ $property->serial_number }}">
            </div>
            <div class="col-md-6 col-sm-12 mb-3">
                <label for="property_condition" class="form-label">Property Condition</label>
                <input type="text" class="form-control" id="property_condition" name="property_condition" value="{{ $property->property_condition }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-12 mb-3">
                <label for="accumulated_depreciation" class="form-label">Accumulated Depreciation</label>
                <input type="text" class="form-control" id="accumulated_depreciation" name="accumulated_depreciation" value="{{ $property->accumulated_depreciation }}">
            </div>
            <div class="col-md-6 col-sm-12 mb-3">
                <label for="accumulated_impairment_losses" class="form-label">Accumulated Impairment Losses</label>
                <input type="text" class="form-control" id="accumulated_impairment_losses" name="accumulated_impairment_losses" value="{{ $property->accumulated_impairment_losses }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-12 mb-3">
                <label for="carrying_amount" class="form-label">Carrying Amount</label>
                <input type="text" class="form-control" id="carrying_amount" name="carrying_amount" value="{{ $property->carrying_amount }}">
            </div>
            <div class="col-md-6 col-sm-12 mb-3">
                
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-end">
                <button class="btn btn-primary" type="submit">Submit <em class="bi bi-caret-right-fill"></em></button>
            </div>
        </div>
    </form>

    <x-slot:additional_script>
    </x-slot>
</x-dashboard-layout>