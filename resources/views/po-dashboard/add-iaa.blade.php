<x-dashboard-layout>
    <x-slot:title>
        Add New Inspection and Acceptance
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Inspection and Acceptance Report', 'route' => 'ia.all'],
            ['name' => 'Add New Inspection and Acceptance Report'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <form method="POST" action="{{ route('ia.post') }}">
                @csrf
                <div class="mb-3">
                    <label for="iar_number" class="form-label">I.A.R. No.:</label>
                    <input type="text" class="form-control" id="iar_number" name="iar_number" placeholder="Please enter inspection and acceptance report number here.">
                </div>
                <div class="mb-3">
                    <label for="iar_date" class="form-label">I.A.R. Date:</label>
                    <input type="date" class="form-control" id="iar_date" name="iar_date">
                </div>
                <div class="mb-3">
                    <label for="purchase_order" class="form-label">Purchase Order:</label>
                    <select id="purchase_order" name="purchase_order" class="form-select" aria-label="Select purchase order">
                        <option disabled selected>Select which purchase order</option>
                        @foreach ($po_list as $po)
                            <option value="{{ $po->id }}">{{ $po->po_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="branch" class="form-label">Branch:</label>
                    <select id="branch" name="branch" class="form-select" aria-label="Select branch">
                        <option disabled selected>Select branch</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="rcc" class="form-label">Responsibility Center Code:</label>
                    <input type="text" class="form-control" id="rcc" name="rcc" placeholder="Please enter responsibility center code here.">
                </div>
                <div>
                    <button class="btn btn-primary" type="submit"><em class="bi bi-save2"></em> Save Report</button>
                    <button class="btn btn-danger float-end" type="submit"><em class="bi bi-x-lg"></em> Cancel</button>
                </div>
            </form>
        </div>
        <div class="col-sm-12 col-md-6"></div>
    </div>
    
    <x-slot:additional_script>
        
    </x-slot>
</x-dashboard-layout>