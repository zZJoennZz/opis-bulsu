<x-dashboard-layout>
    <x-slot:title>
        Add Inventory Custodian Form
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>'],
            ['name' => 'Add Inventory Custodian Form'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />

    <div class="row mb-2">
        <div class="col-12">
            <div class="small fst-italic text-uppercase text-secondary">
                ICS No. will be generated once submitted.
            </div>
        </div>
    </div>

    <form action="{{ route('icf.store') }}" method="post">
        @csrf
        <div class="row mb-2">
            <div class="col-12">
                <label for="date_acquired" class="form-label">Date Acquired:</label>
                <input type="date" class="form-control" id="date_acquired" name="date_acquired" required>
            </div>
        </div>
    
        <div class="row mb-2">
            <div class="col-sm-12 col-md-6">
                <label for="inspection_and_acceptances_id" class="form-label">Inspection and Acceptance:</label>
                <select name="inspection_and_acceptances_id" id="inspection_and_acceptances_id" class="form-select" required>
                    <option value="" disabled selected hidden>Select inspection and acceptance record</option>
                    @foreach ($iars as $iar)
                        <option value="{{ $iar->id }}">{{ $iar->iar_no }} (PO#: {{ $iar->purchase_order->po_number }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-12 col-md-6 m-auto" id="item_data">
                <div class="text-secondary small fst-italic">Select IAR number first!</div>
            </div>
        </div>
    
        <div class="row mb-2">
            <div class="col-12" id="item_info">
            </div>
        </div>
    
        <div class="row mb-2">
            <div class="col-12">
                <label for="received_from" class="form-label">Received From:</label>
                <select name="received_from" id="received_from" class="form-select" required>
                    <option value="" disabled selected hidden>Select sender</option>
                    @foreach ($supply_employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->middle_name }} {{ $employee->last_name }} ({{ $employee->position->name }})</option>
                    @endforeach
                </select>
            </div>
        </div>
    
        <div class="row mb-2">
            <div class="col-12">
                <label for="received_by" class="form-label">Received By:</label>
                <select name="received_by" id="received_by" class="form-select" required>
                    <option value="" disabled selected hidden>Select end user</option>
                    @foreach ($supply_end_user as $enduser)
                        <option value="{{ $enduser->id }}">{{ $enduser->first_name }} {{ $enduser->middle_name }} {{ $enduser->last_name }} ({{ $enduser->position->name }} - {{ $enduser->branch->branch_name }})</option>
                    @endforeach
                </select>
            </div>
        </div>
    
        <div class="row mb-2">
            <div class="col-12">
                <label for="date_issued" class="form-label">Date Issued:</label>
                <input type="date" class="form-control" id="date_issued" name="date_issued" value="{{ date('Y-m-d') }}" required>
            </div>
        </div>
    
        <div class="row mb-2">
            <div class="col-12" id="supplier">
                <label for="delivered_by" class="form-label">Delivered By:</label>
                <input type="text" class="form-control" id="delivered_by" name="delivered_by" placeholder="Select IAR first" required readonly>
            </div>
        </div>
    
        <div class="row mb-2">
            <div class="col-12" id="supplier">
                <label for="source_of_fund" class="form-label">Fund Cluster:</label>
                <div class="row">
                    <div class="col-3">
                        <select name="source_of_fund" id="source_of_fund" class="form-select" required>
                            <option value="" disabled selected hidden>Select source of fund</option>
                            @foreach ($source_of_fund as $fund)
                                <option value="{{ $fund->id }}">{{ $fund->source_of_fund }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3"><input type="text" class="form-control" id="fund_cluster_year" name="fund_cluster_year" placeholder="Enter fund cluster year" required readonly value="{{ getPpmpYear() }}"></div>
                    <div class="col-3"><input type="text" class="form-control" id="fund_cluster_month" name="fund_cluster_month" placeholder="Enter fund cluster month" required readonly value="{{ date('m') }}"></div>
                    <div class="col-3"><input type="number" class="form-control" id="fund_cluster_series" name="fund_cluster_series" placeholder="Enter fund cluster series" required></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary">Submit <em class="bi bi-chevron-double-right"></em></button>
            </div>
        </div>
    </form>

    <x-slot:additional_script>
        <script src="{{ asset('build/assets/app.b487754a.js') }}"></script>
        <script>
            $(window).ready(function () {
                $('#inspection_and_acceptances_id').change(function (e) {
                    let item_data_div = $('#item_data');
                    let supplier_data = $('#supplier');
                    axios.get(`{{ route("po_by_iar.get") }}/${e.target.value}`)
                        .then((res) => {
                            let $itemDataLabel = $("<label>", {
                                for: "b_a_c_reso_items_id",
                                class: "form-label",
                                text: "Item:"
                            });
                            let $itemDataSelect = $("<select>", {
                                id: "b_a_c_reso_items_id",
                                name: "b_a_c_reso_items_id",
                                class: "form-select",
                                required: true,
                            });
                            $itemDataSelect.append($('<option>', {
                                value: "",
                                text: 'Select item here',
                                disabled: true,
                                selected: true,
                                hidden: true,
                            }));

                            res.data.data.forEach((i) => {
                                $itemDataSelect.append($('<option>', {
                                    value: i.id,
                                    text: i.quotation.pr_item.ppmp.item_detail.description,
                                }));
                            });

                            $itemDataSelect.change(getItem);
                            item_data_div.html('');
                            item_data_div.append($itemDataLabel);
                            item_data_div.append($itemDataSelect);
                            $('#delivered_by').val(res.data.data[0].bac_reso.purchase_order.company.name);
                        })
                        .catch((err) => console.log(err));
                });

                async function getItem(e) {
                    await axios.get(`{{ route('bac_reso_item.get') }}/${e.target.value}`)
                        .then((res) => {
                            const itemInfoDiv = $('#item_info');
                            let itemData = res.data.data[0];
                            
                            let $itemInfoCard = $("<div>", {
                                class: "card text-bg-primary",
                            });
                            let $itemInfoCardHeader = $("<div>", {
                                class: "card-header"
                            });
                            let $itemInfoCardTitle = $("<h5>", {
                                class: "card-title mb-2"
                            });
                            let $itemInfoCardBody = $("<div>", {
                                class: "card-body"
                            });

                            let totalQty = 0;
                            itemData.quotation.pr_item.ppmp.milestones.forEach((m) => {
                                totalQty += m.milestone_value;
                            })

                            itemInfoDiv.html('');
                            $itemInfoCardTitle.append(itemData.quotation.pr_item.ppmp.item_detail.description);
                            $itemInfoCardBody.append($itemInfoCardTitle);
                            $itemInfoCardBody.append(`
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <div class="small text-uppercase">Article:</div>
                                        <div class="fw-bold">${itemData.quotation.pr_item.ppmp.item_detail.article}</div>
                                        <div class="fw-bold">${itemData.quotation.pr_item.ppmp.item_detail.extra_article || ''}</div>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <div class="small text-uppercase">Quantity/Unit:</div>
                                        <div class="fw-bold">${totalQty} ${itemData.quotation.pr_item.ppmp.item_detail.unit.uom}</div>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <div class="small text-uppercase">Unit Cost:</div>
                                        <div class="fw-bold">${convertToCurrency(Number(itemData.quotation.offered_unit_price))}</div>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <div class="small text-uppercase">Total Cost:</div>
                                        <div class="fw-bold">${convertToCurrency(Number(totalQty * itemData.quotation.offered_unit_price))}</div>
                                    </div>
                                    <div class="col-12">
                                        <label for="serial_number" class="small text-uppercase">Serial Number:</label>
                                        <div><input type="text" class="form-control" name="serial_number" id="serial_number" placeholder="Enter item serial number" required></div>
                                    </div>
                                </div>
                            `);
                            $itemInfoCardHeader.append("Item Detail");
                            $itemInfoCard.append($itemInfoCardHeader);
                            $itemInfoCard.append($itemInfoCardBody);
                            itemInfoDiv.append($itemInfoCard);
                        });
                }
            });
        </script>
    </x-slot>
</x-dashboard-layout>