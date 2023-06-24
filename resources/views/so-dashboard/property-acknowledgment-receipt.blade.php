<x-dashboard-layout>
    <x-slot:title>
        Property Acknowledgment Receipt
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Property Acknowledgment Receipt'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    <form id="par-form">
        <div class="row mb-3">
            <div class="col-12">
                <label for="purchase_orders_id" class="form-label">Purchase Order #</label>
                <select id="purchase_orders_id" name="purchase_orders_id" class="form-select" aria-label="Select purchase order here">
                    <option selected hidden disabled>Select purchase order here</option>
                    @foreach ($par_po as $po)
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
                <div class="mb-2">
                    <label for="issued_by" class="form-label">Issued By</label>
                    <select id="issued_by" name="issued_by" class="form-select" aria-label="Issued by">
                        <option selected hidden disabled>Open to select</option>
                        @foreach ($supply_emp as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->middle_name }} {{ $emp->last_name }} ({{ $emp->position->name }})</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" class="btn btn-sm btn-secondary w-100" id="add-issued-by"><em class="bi bi-plus-circle-fill"></em> Add</button>
            </div>
            <div class="col-sm-12 col-md-6">
                <div id="list-of-issued-by">
                    <span class="fst-italic small text-muted text-uppercase">Selected issuer/s</span>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-12 col-md-6">
                <div class="mb-2">
                    <label for="received_by" class="form-label">Received By</label>
                    <select id="received_by" name="received_by" class="form-select" aria-label="Received by">
                        <option selected hidden disabled>Open to select</option>
                        @foreach ($end_users as $user)
                            <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }} ({{ $user->position->name }} / {{ $user->branch->branch_name }})</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" class="btn btn-sm btn-secondary w-100" id="add-received-by"><em class="bi bi-plus-circle-fill"></em> Add</button>
            </div>
            <div class="col-sm-12 col-md-6">
                <div id="list-of-received-by">
                    <span class="fst-italic small text-muted text-uppercase">Selected receiver/s</span>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                <label for="branch" class="form-label">Branch</label>
                <select id="branch" name="branch" class="form-select" aria-label="Branch">
                    <option selected hidden disabled>Open to select</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
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
            let modeType = "PAR";
            let selIssuedBy = [];
            let selReceivedBy = [];

            $(document).ready(function () {
                $('#add-issued-by').click(async function (event) {
                    $(this).prop("disabled", true).html("Adding...");

                    const issuedBy = $('#issued_by').val();

                    try {
                        const res = await axios.get(`{{ route('supplyemployee.single') }}/${issuedBy}`);
                        const supEmpId = res.data;

                        const isAlreadyAdded = selIssuedBy.some(data => data.id === supEmpId.id);
                        if (!isAlreadyAdded) {
                            selIssuedBy.push({
                                id: supEmpId.id,
                                first_name: supEmpId.first_name,
                                middle_name: supEmpId.middle_name,
                                last_name: supEmpId.last_name,
                                position: supEmpId.position.name,
                            });

                            // Add the name to the list
                            const fullName = `${supEmpId.first_name} ${supEmpId.middle_name} ${supEmpId.last_name}`;
                            const position = supEmpId.position.name;
                            const listItem = `
                                <div class="issued-by-item mb-1" data-id="${supEmpId.id}">
                                    <button type="button" class="remove-issued-by btn btn-danger" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;"><em class="bi bi-x"></em></button>
                                    <span class="name">${fullName}</span>
                                    <span class="position badge bg-secondary">${position}</span>
                                </div>
                            `;
                            $('#list-of-issued-by').append(listItem);

                            // Attach click event to remove button
                            $(`[data-id="${supEmpId.id}"] .remove-issued-by`).click(function() {
                                const idToRemove = parseInt($(this).parent().data('id'));
                                selIssuedBy = selIssuedBy.filter(item => item.id !== idToRemove);
                                $(this).parent().remove();
                            });
                        } else {
                            alert("Already added.");
                        }
                    } catch (err) {
                        alert(err.response.data.message);
                    }
                    $(this).prop("disabled", false).html(`<em class="bi bi-plus-circle-fill"></em> Add`);
                });

                $('#add-received-by').click(async function (event) {
                    $(this).prop("disabled", true).html("Adding...");

                    const receivedBy = $('#received_by').val();

                    try {
                        const res = await axios.get(`{{ route('enduser.single') }}/${receivedBy}`);
                        const endUserData = res.data;

                        const isAlreadyAdded = selReceivedBy.some(data => data.id === endUserData.id);
                        if (!isAlreadyAdded) {
                            selReceivedBy.push({
                                id: endUserData.id,
                                first_name: endUserData.first_name,
                                middle_name: endUserData.middle_name,
                                last_name: endUserData.last_name,
                                position: endUserData.position.name,
                                branch: endUserData.branch.branch_name
                            });

                            // Add the name to the list
                            const fullName = `${endUserData.first_name} ${endUserData.middle_name} ${endUserData.last_name}`;
                            const position = endUserData.position.name;
                            const branch = endUserData.branch.branch_name;
                            const listItem = `
                                <div class="received-by-item mb-1" data-id="${endUserData.id}">
                                    <button type="button" class="remove-received-by btn btn-danger" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;"><em class="bi bi-x"></em></button>
                                    <span class="name">${fullName}</span>
                                    <span class="position badge bg-secondary">${position} / ${branch}</span>
                                </div>
                            `;
                            $('#list-of-received-by').append(listItem);

                            // Attach click event to remove button
                            $(`[data-id="${endUserData.id}"] .remove-received-by`).click(function() {
                                const idToRemove = parseInt($(this).parent().data('id'));
                                selReceivedBy = selReceivedBy.filter(item => item.id !== idToRemove);
                                $(this).parent().remove();
                            });
                        } else {
                            alert("Already added.");
                        }
                    } catch (err) {
                        alert(err.response.data.message);
                    }
                    $(this).prop("disabled", false).html(`<em class="bi bi-plus-circle-fill"></em> Add`);
                });

            
                $('#par-form').submit(function(event) {
                    event.preventDefault(); // Prevent the form from submitting normally

                    // Validation for fields not being empty
                    let purchaseOrderId = $('#purchase_orders_id').val();
                    let dateAcquired = $('#date_acquired').val();
                    let dateIssued = $('#date_issued').val();
                    let branch = $('#branch').val();

                    if (
                        purchaseOrderId === null ||
                        purchaseOrderId.trim() === '' ||
                        dateAcquired.trim() === '' ||
                        dateIssued.trim() === '' ||
                        branch.trim() === '' ||
                        selIssuedBy.length === 0 ||
                        selReceivedBy.length === 0
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

                    let selectedIssuer = [];
                    let selectedReceiver = [];

                    selIssuedBy.forEach((issuer) => selectedIssuer.push(issuer.id));
                    selReceivedBy.forEach((receiver) => selectedReceiver.push(receiver.id));

                    axios.post(`{{ route('par.save') }}`, {
                        purchaseOrderId: purchaseOrderId,
                        dateAcquired: dateAcquired,
                        dateIssued: dateIssued,
                        issuedBy: selectedIssuer,
                        receivedBy: selectedReceiver,
                        purchaseOrderItems: purchaseOrderItems,
                        branch: branch,
                    })
                    .then(function(response) {
                        window.location.href = response.data.redirect;
                        console.log(response);
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