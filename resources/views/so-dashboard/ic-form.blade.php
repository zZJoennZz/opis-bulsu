<x-dashboard-layout>
    <x-slot:title>
        Inventory Custodian Form
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Inventory Custodian Form'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1 class="h3">ICS - Inventory Custodian Form</h1>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12"><h2 class="h5 text-secondary">Add New ICS</h2></div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="mb-3">
                    <label for="date_acquired" class="form-label">Date Acquired</label>
                    <input type="date" class="form-control" id="date_acquired" name="date_acquired">
                </div>
                <div class="mb-3">
                    <label for="iar" class="form-label">Inspection and Acceptance Report</label>
                    <select id="iar" name="iar" class="form-select" aria-label="Select Inspection and Acceptance Report">
                        <option disabled selected>Select inspection and acceptance report</option>
                        @foreach ($iaas as $i)
                            <option value="{{ $i->id }}">{{ $i->iar_no }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <div class="card p-3">
                        <div class="fw-bold mb-3">Item (PO #: 123)</div>
                        <div class="mb-3">
                            <label for="item_description" class="form-label">Item Description</label>
                            <select id="item_description" name="item_description" class="form-select" aria-label="Select item">
                                <option disabled selected>Select item description</option>
                            </select>
                        </div>
                        <div class="mb-3 bg-primary text-light p-2 rounded">
                            <small class="text-uppercase fw-bold">Selected item</small>
                            <div class="small">
                                <span class="fw-bold">Item:</span> Paper (Article here)
                            </div>
                            <div class="small">
                                <span class="fw-bold">Quantity:</span> 999 PCS
                            </div>
                            <div class="small">
                                <span class="fw-bold">Cost:</span> ₱ 100 <span class="fw-bold">Total Cost:</span> ₱ 99,900
                            </div>
                        </div>
                        <div>
                            <label for="serial_number" class="form-label">Serial Number</label>
                            <input type="text" class="form-control" id="serial_number" name="serial_number" placeholder="Enter serial number here.">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="mb-3">
                    <label for="received_from" class="form-label">Received From</label>
                    <select id="received_from" name="received_from" class="form-select" aria-label="Select received from">
                        <option disabled selected>Select received from</option>
                        @foreach ($supply_employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->first_name . ' ' . substr($employee->middle_name, 0, 1) . ' ' . $employee->last_name }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted" id="employee-position">ADMIN</small>
                </div>
                <div class="mb-3">
                    <label for="received_from" class="form-label">Received By</label>
                    <select id="received_from" name="received_from" class="form-select" aria-label="Select received from">
                        <option disabled selected>Select received from</option>
                        @foreach ($end_users as $user)
                            <option value="{{ $user->id }}">{{ $user->first_name . ' ' . substr($user->middle_name, 0, 1) . ' ' . $user->last_name }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted" id="employee-position">PROFESSOR</small>
                </div>
                <div class="mb-3">
                    <label for="date_issued" class="form-label">Date Issued</label>
                    <input type="date" class="form-control" id="date_issued" name="date_issued">
                </div>
                <div class="mb-3">
                    <label for="delivered_by" class="form-label">Delivered By</label>
                    <input type="text" class="form-control" id="delivered_by" name="delivered_by">
                </div>
                <div class="mb-3">
                    <label for="source_of_fund" class="form-label">Source of Fund</label>
                    <select id="source_of_fund" name="source_of_fund" class="form-select" aria-label="Select source of fund">
                        <option disabled selected>Select source of fund</option>
                        @foreach ($source_of_funds as $sof)
                            <option value="{{ $sof->id }}">{{ $sof->source_of_fund }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <x-slot:additional_script>
        <script>
            let allData = JSON.parse(`{!! json_encode($iaas) !!}`);
            $(document).ready(function () {
                $('#iar').on('change', function () {
                    $('#item_description').empty();
                    let o = $("<option disabled selected></option>");
                    o.text("Select item description");
                    $('#item_description').append(o);
                    const selData = allData.filter(e => parseInt(e.id) === parseInt(window.event.target.value))[0];
                    selData.purchase_order.canvass_abstract.items.forEach((el) => {
                        let option = $("<option></option>");
                        option.text(el.quotation_item.pr_item.ppmp.item_detail.description);
                        option.val(el.quotation_item.id);
                        $('#item_description').append(option);
                    });
                });

                $('#item_description').on('change', function () {
                    console.log(window.event.target.value);
                });
            });
        </script>
    </x-slot>
</x-dashboard-layout>