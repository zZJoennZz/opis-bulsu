<x-dashboard-layout>
    <x-slot:title>
        Request for Quotation
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Request for Quotation', 'route' => 'rfq.index'],
            ['name' => 'Create New'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    
    <div class="row">
        <div class="col-12">
            <form action="{{ route('rfq.create') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="purchase_requests_id" class="form-label">Select purchase request:</label>
                    <select class="form-select" id="purchase_requests_id" name="purchase_requests_id" aria-label="Select purchase request" required>
                        <option value="" disabled selected hidden>Select purchase request</option>
                        @foreach ($purchase_requests as $pr)
                            <option value="{{ $pr->id }}">{{ $pr->pr_number }}</option>
                        @endforeach
                    </select>
                    <div class="mt-1" id="view-items-btn">
                        
                    </div>
                </div>
                <div class="mb-3">
                    <label for="deadline_of_submission" class="form-label">Deadline of Submission:</label>
                    <input type="date" class="form-control" id="deadline_of_submission" name="deadline_of_submission" placeholder="Please enter inspection and acceptance report number here.">
                </div>
                <div class="mb-3">
                    <label for="mode_of_procurements_id" class="form-label">Select purchase request:</label>
                    <select class="form-select" id="mode_of_procurements_id" name="mode_of_procurements_id" aria-label="Select mode of procurement" required>
                        <option value="" disabled selected hidden>Select mode of procurement</option>
                        @foreach ($mode_of_procurements as $mop)
                            <option value="{{ $mop->id }}">{{ $mop->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="approved_budget" class="form-label">Approved Budget:</label>
                    <input type="text" class="form-control" id="approved_budget" name="approved_budget" placeholder="Please enter approved budget here.">
                </div>
                <div class="mb-3">
                    <label for="buyer_name" class="form-label">Buyer's Full Name:</label>
                    <input type="text" class="form-control" id="buyer_name" name="buyer_name" placeholder="Please enter buyer's full name.">
                </div>
                <div class="mb-3">
                    <label for="head_procurement" class="form-label">Head of Procurement:</label>
                    <input type="text" class="form-control" id="head_procurement" name="head_procurement" placeholder="Please enter head of procurement's full name." value="{{ getSettingValue('head_asset_management_unit') }}">
                </div>
                <div>
                    <button class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <x-slot:additional_script>
        {{-- @include('layout/datatable', ['tableId' => 'rfq-table']) --}}
        <script>
            $(document).ready(function() {
                $('#purchase_requests_id').on('change', function() {
                    var selectedValue = $(this).val();
                    $('#view-items-btn').html(`<a target="_blank" href="{{ route('pr.get') }}/${selectedValue}">View Items</a>`);
                });
            });

        </script>
    </x-slot>
</x-dashboard-layout>