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
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12 col-md 6">
                            <div class="mb-3">
                                <label for="bac_reso" class="form-label">BAC Resolution <small class="text-muted">Items under this BAC will also display below.</small></label>
                                <select id="bac_reso" name="bac_reso" class="form-select" aria-label="Select BAC Resolution">
                                    <option value="0" disabled selected>Open this to select BAC resolution</option>
                                    @foreach ($bac_reso as $bac)
                                        <option value="{{ $bac->id }}">{{ $bac->b_a_c_reso_number }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="company" class="form-label">Company</label>
                                <select id="company" name="company" class="form-select" aria-label="Select company">
                                    <option value="0" disabled selected>Select BAC resolution first</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="mode_of_procurement" class="form-label">Mode of Procurement</label>
                                <select id="mode_of_procurement" name="mode_of_procurement" class="form-select" aria-label="Select mode of procurement">
                                    <option value="0" disabled selected>Open this to select mode of procurement</option>
                                    @foreach ($mode_of_procurement as $mop)
                                        <option value="{{ $mop->id }}">{{ $mop->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="accountant_name" class="form-label">Accountant</label>
                                <select id="accountant_name" name="accountant_name" class="form-select" aria-label="Select mode of procurement">
                                    <option value="0" disabled selected>Open this to select accountant name</option>
                                    @foreach (json_decode(json_decode(getSettingValue('accountants')), true) as $acct)
                                        <option value="{{ $acct['id'] }}">{{ $acct['full_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md 6">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="fw-bold mb-3">Delivery Information</div>
                                    <div class="mb-3">
                                        <label for="place_of_delivery" class="form-label">Place of Delivery</label>
                                        <input type="text" name="place_of_delivery" id="place_of_delivery" class="form-control" value="SUPPLY OFFICE CSSP BLDG / Tel: 044-919-7800 loc. 1055">
                                    </div>
                                    <div class="mb-3">
                                        <label for="date_of_delivery" class="form-label">Date of Delivery</label>
                                        <input type="text" name="date_of_delivery" id="date_of_delivery" class="form-control" value="Office Hour: 8:00 am - 5:00 pm (M-F)">
                                    </div>
                                    <div class="mb-3">
                                        <label for="for_inquiry" class="form-label">For Inquiry</label>
                                        <input type="text" name="for_inquiry" id="for_inquiry" class="form-control" value="PROCUREMENT OFFICE CSSP BLDG / Tel: 044-919-7800 loc. 1056">
                                    </div>
                                    <div class="mb-3">
                                        <label for="delivery_term" class="form-label">Delivery Term</label>
                                        <input type="text" name="delivery_term" id="delivery_term" class="form-control" value="7 days">
                                    </div>
                                    <div class="mb-3">
                                        <label for="mode_of_payment" class="form-label">Payment Term</label>
                                        <select id="mode_of_payment" name="mode_of_payment" class="form-select" aria-label="Select mode of payment">
                                            <option value="0" disabled selected>Open this to select mode of payment</option>
                                            @foreach ($mode_of_payment as $mop)
                                                <option value="{{ $mop->id }}">{{ $mop->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
            let COMPANY_BY_BAC_RESO = `{{ route('company-bac.get') }}`;
            $(window).on('load', function () {
                $('#generate-report').on('click', async function() {
                    this.disabled = true;
                    let bac_reso = $('#bac_reso').val();
                    let mode_of_payment = $('#mode_of_payment').val();
                    let company = $('#company').val();
                    let mode_of_procurement = $('#mode_of_procurement').val();
                    let place_of_delivery = $('#place_of_delivery').val();
                    let date_of_delivery = $('#date_of_delivery').val();
                    let for_inquiry = $('#for_inquiry').val();
                    let delivery_term = $('#delivery_term').val();
                    let accountant_name = $('#accountant_name').val();

                    if (bac_reso === null || bac_reso === undefined || bac_reso === '' || bac_reso === 0 || bac_reso === '0') {
                        alert('Please select BAC resolution first.');
                    } else if (mode_of_payment === null || mode_of_payment === undefined || mode_of_payment === '' || mode_of_payment === 0 || mode_of_payment === '0') {
                        alert('Please select mode of payment.');
                    } else if (company === null || company === undefined || company === '' || company === 0 || company === '0') {
                        alert('Please select company.');
                    } else if (mode_of_procurement === null || mode_of_procurement === undefined || mode_of_procurement === '' || mode_of_procurement === 0 || mode_of_procurement === '0') {
                        alert('Please select mode of procurement.');
                    } else if (place_of_delivery === null || place_of_delivery === undefined || place_of_delivery === '' || place_of_delivery === 0 || place_of_delivery === '0') {
                        alert('Please enter place of delivery.');
                    } else if (date_of_delivery === null || date_of_delivery === undefined || date_of_delivery === '' || date_of_delivery === 0 || date_of_delivery === '0') {
                        alert('Please enter date of delivery.');
                    } else if (for_inquiry === null || for_inquiry === undefined || for_inquiry === '' || for_inquiry === 0 || for_inquiry === '0') {
                        alert('Please enter inquiry channel.');
                    } else if (delivery_term === null || delivery_term === undefined || delivery_term === '' || delivery_term === 0 || delivery_term === '0') {
                        alert('Please enter delivery term.');
                    } else if (accountant_name === null || accountant_name === undefined || accountant_name === '' || accountant_name === 0 || accountant_name === '0') {
                        alert('Please select accountant.');
                    } else {
                        let data = {
                            bac_reso,
                            company,
                            mode_of_payment,
                            mode_of_procurement,
                            place_of_delivery,
                            date_of_delivery,
                            for_inquiry,
                            delivery_term,
                            accountant_name
                        }
                        await axios.post(BAC_RESO_POST_URL, data)
                            .then(res => {
                                console.log(res);
                                window.location.href = `{{ route('po.all') }}`;
                            })
                            .catch(err => {
                                window.location.reload();
                            });
                    }

                    this.disabled = false;
                });

                $('#bac_reso').on('change', function () {
                    $('#bac-item-list').html(`
                        <tr>
                            <td colspan="5" class="text-center">
                                No data available in table
                            </td>
                        </tr>
                    `);
                    $('#generate-report').prop('disabled', true);
                    axios.get(`${COMPANY_BY_BAC_RESO}/${window.event.target.value}`)
                        .then((res) => {
                            $('#company').empty();
                            res.data.data.forEach((company) => {
                                $('#company').append($('<option>', {
                                    value: company.id,
                                    text: company.name
                                }));
                            });
                            getItems($('#company').val())
                            $('#generate-report').prop('disabled', true);
                        })
                        .catch((err) => {
                            console.log(err);
                            $('#generate-report').prop('disabled', true);
                        })
                });

                $('#company').on('change', function () {
                    $('#generate-report').prop('disabled', true);
                    getItems(window.event.target.value);
                });
            });

            async function getItems(companyId) {
                await axios.get(`${BAC_RESO_SINGLE_URL}/${$('#bac_reso').val()}/${companyId}`)
                    .then((res) => {
                        let data = res.data.data[0];
                        let bactbody = $('#bac-item-list');
                        let htmlContent = ``;
                        // console.log(data);
                        data.bac_reso_items.forEach(el => {
                            if (parseInt($('#company').val()) === parseInt(el.quotation.quotation.companies_id)) {
                                let totalQty = 0;
                                el.quotation.pr_item.ppmp.milestones.forEach(milestone => {
                                    totalQty += milestone.milestone_value
                                })
                                htmlContent += `
                                    <tr>
                                        <td>${el.quotation.pr_item.ppmp.item_detail.description}</td>
                                        <td>${totalQty}</td>
                                        <td>${el.quotation.pr_item.ppmp.item_detail.unit.uom}</td>
                                        <td>₱ <div class="float-end">${el.quotation.offered_unit_price}</div></td>
                                        <td>₱ <div class="float-end">${(el.quotation.offered_unit_price * totalQty).toFixed(2)}</div></td>
                                    </tr>
                                `;
                            }
                        });
                        bactbody.html(htmlContent);
                        $('#generate-report').prop('disabled', false);
                    })
                    .catch((err) => {
                        console.error(err);
                        $('#generate-report').prop('disabled', false);
                    });
            }
        </script>
        @include('layout/datatable', ["tableId" => "bac-items"])
    </x-slot>
</x-dashboard-layout>
