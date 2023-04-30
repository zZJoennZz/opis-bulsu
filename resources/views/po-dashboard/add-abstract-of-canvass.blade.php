<x-dashboard-layout>
    <x-slot:title>
        Add Abstract of Canvass
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>'],
            ['name' => 'Abstract of Canvasses <span class="badge bg-primary">'. Auth::user()->ppmp_year .'</span>', 'route' => 'aoc.all'],
            ['name' => 'Generate'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />

    <form action="{{ route('aoc.perform') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-12"><label for="purchase_requests_id" class="form-label">Purchase Request</label></div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-12 col-md-10">
                <select class="form-select" id="purchase_requests_id" name="purchase_requests_id" aria-label="Select purchase request" required>
                    <option value="" disabled selected>Select purchase request</option>
                    @foreach ($pr_without_abstract as $pr)
                        <option value="{{ $pr->id }}">{{ $pr->pr_number }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-12 col-md-2 p-1 text-center"><button type="button" id="view_pr" class="btn btn-success btn-sm"><em class="bi bi-eye-fill"></em> View Items</butt></div>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                <label for="type" class="form-label">Type</label>
                <select name="type" id="type" class="form-select" aria-label="Select type" required>
                    <option value="0" disabled selected>Select type</option>
                    <option value="BY_LOT">By lot</option>
                    <option value="BY_ITEM">By item</option>
                </select>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                <label for="abc" class="form-label">ABC</label>
                <input type="number" class="form-control" id="abc" name="abc" title="number" placeholder="Type amount" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-12 col-md-6 col-md-6">
                <label for="bac_chairman" class="form-label">BAC Chairman</label>
                <input type="text" class="form-control" id="bac_chairman" name="bac_chairman" value="{{ getSettingValue("bac_chairman") }}" required>
            </div>
            <div class="col-sm-12 col-md-6 col-md-6">
                <label for="vice_chairman" class="form-label">Vice Chairman</label>
                <input type="text" class="form-control" id="vice_chairman" name="vice_chairman" value="{{ getSettingValue("vice_chair_1") }}" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-12 col-md-6 col-md-6">
                <label for="member_1" class="form-label">Member</label>
                <input type="text" class="form-control" id="member_1" name="member_1" value="{{ getSettingValue("member_1") }}" required>
            </div>
            <div class="col-sm-12 col-md-6 col-md-6">
                <label for="member_2" class="form-label">Member</label>
                <input type="text" class="form-control" id="member_2" name="member_2" value="{{ getSettingValue("member_2") }}" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-12 col-md-6 col-md-6">
                <label for="member_3" class="form-label">Member</label>
                <input type="text" class="form-control" id="member_3" name="member_3" value="{{ getSettingValue("member_3") }}" required>
            </div>
            <div class="col-sm-12 col-md-6 col-md-6">
                <label for="member_4" class="form-label">Member</label>
                <input type="text" class="form-control" id="member_4" name="member_4" value="{{ getSettingValue("member_4") }}" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-12 col-md-6 col-md-6">
                <label for="technical_resource_person" class="form-label">Technical Resource Person</label>
                <input type="text" class="form-control" id="technical_resource_person" name="technical_resource_person" value="{{ getSettingValue("technical_resource_person") }}" required>
            </div>
            <div class="col-sm-12 col-md-6">
                <label for="procurement_office_rep" class="form-label">Procurement Office's Representative</label>
                <input type="text" class="form-control" id="procurement_office_rep" name="procurement_office_rep" value="{{ Auth::user()->profile->first_name . ' ' . Auth::user()->profile->last_name }}" required>
            </div>
        </div>
        <div class="row mb-3">
            
            <div class="col-sm-12 col-md-6">
                <label for="president" class="form-label">University President</label>
                <input type="text" class="form-control" id="president" name="president" value="{{ getSettingValue("university_president") }}" required>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-end">
                <button class="btn btn-primary" type="submit"><em class="bi bi-save2"></em> Save</button>
            </div>
        </div>
    </form>
    <div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="itemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title fs-5" id="itemModalLabel">Items</div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm table-bordered border-dark">
                        <caption>List of items under the selected purchase request.</caption>
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Unit</th>
                                <th>Price Catalogue</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody id="view_item_table"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <x-slot:additional_script>
        @vite('resources/js/app.js')
        
        <script>
            async function getProducts() {
                let prId = $('#purchase_requests_id').val();
                if (prId === '' || prId === 0 || prId === null) {
                    alert('Select purchase request to view.');
                    return;
                }
                await axios.get(`{{ route('pr-single.api') }}/${prId}`)
                    .then((res) => {
                        let items = res.data[0].pr_items;
                        let tableContent = ``;
                        let totalAmount = 0;
                        items.forEach((el) => {
                            let itemQty = 0;
                            el.ppmp.milestones.forEach((m) => {
                                itemQty += m.milestone_value;
                            });

                            totalAmount += el.ppmp.item_detail.price_catalogue * itemQty;
                            tableContent += `
                                <tr>
                                    <td>
                                        ${el.ppmp.item_detail.description}
                                    </td>
                                    <td>
                                        ${itemQty}
                                    </td>
                                    <td>
                                        ${el.ppmp.item_detail.unit.uom}
                                    </td>
                                    <td>
                                        ₱ ${parseInt(el.ppmp.item_detail.price_catalogue).toFixed(2)}
                                    </td>
                                    <td>
                                        ₱ ${(el.ppmp.item_detail.price_catalogue * itemQty).toFixed(2)}
                                    </td>
                                </tr>
                            `;
                            $('#itemModal').modal('toggle');
                        })
                        tableContent += `
                            <tr>
                                <td colspan="4" class="text-end fw-bold">
                                    Total Amount
                                </td>
                                <td>
                                    ${totalAmount.toFixed(2)}
                                </td>
                            </tr>
                        `;
                        $('#view_item_table').html(tableContent);
                    })
                    .catch((err) => {
                        console.log(err);
                    });
            }

            $(window).on('load', function() {
                $('#view_pr').on('click', function() {
                    getProducts();
                });
            });
        </script>
    </x-slot>
</x-dashboard-layout>