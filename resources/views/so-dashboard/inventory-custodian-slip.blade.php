<x-dashboard-layout>
    <x-slot:title>
        Inventory Custodian Slip
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Inventory Custodian Slip'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    <form id="ics-form">
        <div class="row mb-3">
            <div class="col-12">
                <label for="purchase_orders_id" class="form-label">Purchase Order #</label>
                <select id="purchase_orders_id" name="purchase_orders_id" class="form-select" aria-label="Select purchase order here">
                    <option selected hidden disabled>Select purchase order here</option>
                    @foreach ($ics_po as $po)
                        <option value="{{ $po->id }}">{{ $po->po_number }} / {{ $po->company->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <hr />
            <div class="mb-2">
                <span class="text-uppercase small text-secondary fw-bold">Purchase Order Items:</span>
            </div>
        </div>
        <div class="row">
            <div class="col-12" id="po_items_ics">
                <div class="mb-3 text-secondary small fst-italic">Select <strong>purchase order</strong> to display items.</div>
            </div>
        </div>
        <div class="row">
            <hr />
        </div>
        <div class="row mb-3">
            <div class="col-sm-12 col-md-6">
                <label for="date_acquired" class="form-label">Date Acquired</label>
                <input type="date" class="form-control" id="date_acquired" name="date_acquired" value="{{ date('Y-m-d') }}">
            </div>
            <div class="col-sm-12 col-md-6">
                <label for="date_issued" class="form-label">Date Issued</label>
                <input type="date" class="form-control" id="date_issued" name="date_issued" value="{{ date('Y-m-d') }}">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-12 col-md-6">
                <label for="issued_by" class="form-label">Issued By</label>
                <select id="issued_by" name="issued_by" class="form-select" aria-label="Issued by">
                    <option selected hidden disabled>Open to select</option>
                    @foreach ($supply_emp as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->middle_name }} {{ $emp->last_name }} ({{ $emp->position->name }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-12 col-md-6">
                <label for="received_by" class="form-label">Received By</label>
                <select id="received_by" name="received_by" class="form-select" aria-label="Received by">
                    <option selected hidden disabled>Open to select</option>
                    @foreach ($end_users as $user)
                        <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }} ({{ $user->position->name }} / {{ $user->branch->branch_name }})</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
    <x-slot:additional_script>
        <script defer src="{{ asset('build/assets/app.b487754a.js') }}"></script>
        <script defer>
            @if($type === "ICSL")
                let modeType = "ICSL";
            @endif
            @if($type === "ICSH")
                let modeType = "ICSH";
            @endif
            $(document).ready(function () {
                $('#ics-form').submit(function(event) {
                    event.preventDefault(); // Prevent the form from submitting normally

                    // Validation for fields not being empty
                    let purchaseOrderId = $('#purchase_orders_id').val();
                    let dateAcquired = $('#date_acquired').val();
                    let dateIssued = $('#date_issued').val();
                    let issuedBy = $('#issued_by').val();
                    let receivedBy = $('#received_by').val();

                    if (
                        purchaseOrderId === null ||
                        purchaseOrderId.trim() === '' ||
                        dateAcquired.trim() === '' ||
                        dateIssued.trim() === '' ||
                        issuedBy === null ||
                        issuedBy.trim() === '' ||
                        receivedBy === null ||
                        receivedBy.trim() === ''
                    ) {
                        // Display an error message or perform appropriate action
                        alert('Please fill in all the required fields.');
                        return;
                    }

                    // Validation for purchase order items
                    let purchaseOrderItems = [];
                    let isValid = true;

                    $('.eq_code').each(function(index) {
                        let eqCodeSelect = $(this);
                        let serialNumberTextarea = $('.serial_numbers').eq(index);

                        let eqCode = eqCodeSelect.val();
                        if (eqCode === '' || eqCode === null) {
                            alert('Please select an equipment code for all purchase order items.');
                            isValid = false;
                            return false; // Exit the each loop and prevent form submission
                        }

                        let serialNumbers = serialNumberTextarea.val().trim();
                        let serialNumberLines = [];

                        if (serialNumbers !== '') {
                            serialNumberLines = serialNumbers.split('\n');
                            let itemQuantity = parseInt(eqCodeSelect.closest('tr').find('td:nth-child(2)').text().split(' ')[0], 10);

                            if (serialNumberLines.length !== itemQuantity) {
                                alert(`The number of serial numbers should match the quantity of the item: ${itemQuantity}.`);
                                isValid = false;
                                return false; // Prevent form submission
                            }
                        }

                        purchaseOrderItems.push({
                            equipmentCode: eqCode,
                            serialNumbers: serialNumberLines,
                        });
                    });

                    if (!isValid) {
                        return;
                    }

                    axios.post(`{{ route('ics.save') }}/${modeType}`, {
                        purchaseOrderId: purchaseOrderId,
                        dateAcquired: dateAcquired,
                        dateIssued: dateIssued,
                        issuedBy: issuedBy,
                        receivedBy: receivedBy,
                        purchaseOrderItems: purchaseOrderItems,
                    })
                    .then(function(response) {
                        window.location.href = response.data.redirect;
                    })
                    .catch(function(error) {
                        if (error.response.status === 422) {
                            alert(error.response.data.message);
                        } else {
                            alert("Oops! Something went wrong and your submission could not be processed. Please try again later or contact support for assistance.");
                        }
                    });

                });

                $('#purchase_orders_id').on('change', function() {
                    $('#po_items_ics').html(`
                        <div class="w-100 d-flex justify-content-center mb-3">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    `);
                    let po_id = $('#purchase_orders_id').val();

                    axios.get(`{{ route('po.get-single') }}/${po_id}/${modeType}`)
                        .then((res) => {
                            let eqCode = `
                                <select class="eq_code form-select">
                                    <option selected hidden disabled>Select equipment code</option>
                                    @foreach($eq_code as $eq)
                                        <option value="{{ $eq->id }}">{{ $eq->description }} ({{ $eq->equipment_code }})</option>
                                    @endforeach
                                </select>
                            `;
        
                            let tblRows = ``;
        
                            res.data.data.forEach((item) => {
                                let totalQty = 0;
                                item.quotation.pr_item.ppmp.milestones.forEach((m) => totalQty += m.milestone_value);
                                tblRows += `
                                    <tr class="po_item">
                                        <td>${item.quotation.pr_item.ppmp.item_detail.description}</td>
                                        <td>${totalQty} ${item.quotation.pr_item.ppmp.item_detail.unit.uom}</td>
                                        <td>${convertToCurrency(parseFloat(item.quotation.offered_unit_price))}</td>
                                        <td>${eqCode}</td>
                                        <td>
                                            <textarea class="form-control serial_numbers" name="serial_numbers" placeholder="Enter serial numbers (one per line)"></textarea>
                                        </td>
                                    </tr>
                                `;
                            });
        
                            let content = `
                                <table class="table table-sm border-dark">
                                    <caption></caption>
                                    <thead>
                                        <tr style="font-size: 12px !important;">
                                            <th style="width: 30%;">Description</th>
                                            <th style="width: 10%;">Quantity / Unit</th>
                                            <th style="width: 10%;">Unit Price</th>
                                            <th style="width: 20%;">Equipment Code</th>
                                            <th style="width: 30%;">Serial Numbers</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${tblRows}
                                    </tbody>
                                </table>
                            `;
        
                            $('#po_items_ics').html(content);
                        });
                });
            })
        </script>
    </x-slot>
</x-dashboard-layout>