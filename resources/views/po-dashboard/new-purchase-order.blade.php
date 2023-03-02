<x-dashboard-layout>
    <x-slot:title>
        New Purchase Order
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Purchase Order', 'route' => 'po.all'],
            ['name' => 'New Purchase Order'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />

    <div>
        <div>
            <form action="#">
                <div class="mb-3">
                    <label for="bac_reso" class="form-label">BAC Resolution <small class="text-muted">Items under this BAC will also display below.</small></label>
                    <select id="bac_reso" name="bac_reso" class="form-select" aria-label="Select BAC Resolution">
                        <option value="0" disabled selected>Open this to select BAC resolution</option>
                        @foreach ($bac_reso as $bac)
                            <option value="{{ $bac->id }}">{{ $bac->id }} | {{ $bac->company->name }} | {{ $bac->abc }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="mode_of_payment" class="form-label">Mode of Payment</label>
                    <select id="mode_of_payment" name="name_of_payment" class="form-select" aria-label="Select mode of payment">
                        <option value="0" disabled selected>Open this to select mode of payment</option>
                        @foreach ($mode_of_payment as $mop)
                            <option value="{{ $mop->id }}">{{ $mop->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-primary" id="generate-report"><em class="bi bi-file-earmark-text-fill"></em> Generate Report</button>
                </div>
            </form>
        </div>
        <hr />
        <div class="table-responsive">
            <table id="bac-items" class="table table-sm table-bordered border-dark caption-top">
                <caption>Items in selected BAC</caption>
                <thead>
                    <tr>
                        <th>Item Description</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Unit Cost</th>
                        <th>Total Cost</th>
                    </tr>
                </thead>
                <tbody id="bac-item-list">
                    
                </tbody>
            </table>
        </div>
    </div>

    <x-slot:additional_script>
        <script src="{{ asset('build/assets/app.b487754a.js') }}"></script>
        <script>
            let BAC_RESO_SINGLE_URL = `{{ route('bac-reso.by-id') }}`;
            let BAC_RESO_POST_URL = `{{ route('po.perform') }}`;
            $(window).on('load', function () {
                $('#generate-report').on('click', async function() {
                    this.disabled = true;
                    let bac_reso = $('#bac_reso').val();
                    let mop = $('#mode_of_payment').val();

                    if (bac_reso === null || bac_reso === undefined || bac_reso === '' || bac_reso === 0 || bac_reso === '0') {
                        alert('Please select BAC resolution first.');
                    } else if (mop === null || mop === undefined || mop === '' || mop === 0 || mop === '0') {
                        alert('Please select mode of payment.');
                    } else {
                        let data = {
                            bac_reso,
                            mop
                        }
                        await axios.post(BAC_RESO_POST_URL, data)
                            .then(res => {
                                window.location.href = `{{ route('po.all') }}`;
                            })
                            .catch(err => {
                                window.location.reload();
                            });
                    }

                    this.disabled = false;
                });

                $('#bac_reso').on('change', function () {
                    axios.get(`${BAC_RESO_SINGLE_URL}/${window.event.target.value}`)
                        .then((res) => {
                            let data = res.data.data[0];
                            let bactbody = $('#bac-item-list');
                            let htmlContent = ``;
                            data.items.forEach(el => {
                                let totalQty = 0;
                                el.quotation_item.pr_item.ppmp.milestones.forEach(milestone => {
                                    totalQty += milestone.milestone_value
                                })
                                htmlContent += `
                                    <tr>
                                        <td>${el.quotation_item.pr_item.ppmp.item_detail.description}</td>
                                        <td>${totalQty}</td>
                                        <td>${el.quotation_item.pr_item.ppmp.item_detail.unit.uom}</td>
                                        <td>₱ <div class="float-end">${el.quotation_item.offered_unit_price}</div></td>
                                        <td>₱ <div class="float-end">${(el.quotation_item.offered_unit_price * totalQty).toFixed(2)}</div></td>
                                    </tr>
                                `;
                            });
                            bactbody.html(htmlContent);
                        })
                        .catch((err) => console.error(err));
                });
            });
        </script>
        @include('layout/datatable', ["tableId" => "bac-items"])
    </x-slot>
</x-dashboard-layout>