<x-dashboard-layout>
    <x-slot:title>
        Add New
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'BAC Resolution', 'route' => 'dashboard.show'],
            ['name' => 'Add']
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    <div class="modal modal-lg fade" id="compare-price-modal" tabindex="-1" aria-labelledby="compareItemModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title fs-5 fw-bold" id="compareItemModal"></div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="compare-modal">
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12 col-md-3 p-2 border-end">
            <div>
                <div class="text-muted small mb-2">Select company to view quotations</div>
                <select class="form-select" id="company" aria-label="Select company">
                    <option selected>Select supplier/company</option>
                    @foreach ($company_list as $company)
                        <option value="{{$company->id}}">{{$company->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-sm-12 col-md-9 p-2">
            <div class="fs-5 fw-bold mb-3">Quotations from the company</div>
            <div id="comp-quotations" class="border-bottom pb-3 mb-2">
                <div class="fst-italic text-muted">Please select company with quotations!</div>
            </div>
            <div class="fs-5 fw-bold mb-3">Selected items for the company</div>
            <div id="selected-items" class="mb-3">
                <div class="fst-italic text-muted">No selected items yet!</div>
            </div>
            <div>
                <div class="mb-3">
                    <label for="abc" class="form-label">Enter ABC</label>
                    <input type="text" class="form-control" id="abc" name="abc" disabled>
                </div>
                <div class="d-flex justify-content-end">
                    <button id="save-btn" class="btn btn-primary" disabled>Generate BAC <em class="bi bi-chevron-double-right"></em></button>
                </div>
            </div>
        </div>
    </div>

    
    <x-slot:additional_script>
        <script src="{{ asset('build/assets/app.b487754a.js') }}"></script>
        <script defer>
            let getCompanyQuotationUrl = "{{route('company-quotation.single')}}";
            let getItemComparison = "{{route('quotation-comparison.single')}}";
            let postBacReso = "{{route('bac-reso.perform')}}";
            let doubleCheck = false;
            let selectedItems = [];
            $('document').ready(function() {
                $('#save-btn').on('click', generateBac);
                $('#company').on('change', async function() {
                    selectedItems = [];
                    $('#abc').val('');
                    $('#save-btn').attr('disabled', true);
                    $('#abc').attr('disabled', true);
                    let compQuotationHtml = $('#comp-quotations');
                    compQuotationHtml.html(`
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    `);
                    await axios.get(getCompanyQuotationUrl + '/' + this.value)
                        .then(async (res) => {
                            if (res.status !== 200) {
                                console.log("Something went wrong. Company don't have quotation or runtime error.");
                                compQuotationHtml.html(`<div class="fst-italic text-muted">Please select company with quotations!</div>`)
                            } else {
                                let data = res.data.data[0];
                                let itemRows = ``;
                                data.quotations.map(quote =>
                                    quote.items.map(item =>
                                        {
                                            if (item.ppmp.item_detail !== null) {
                                                itemRows += `
                                                    <tr>
                                                        <td>${item.ppmp.item_detail.description}</td>
                                                        <td>${item.brand_and_model_offered}</td>
                                                        <td>${item.offered_unit_price}</td>
                                                        <td class="d-flex justify-content-center">
                                                            <button class="btn btn-primary btn-sm me-2" onclick="toggleItem(event, ${item.id})"><em class="bi bi-plus"></em> Add</button> <button class="btn btn-secondary btn-sm" onclick="showComparison(${item.ppmp.item_detail.id})"><em class="bi bi-table"></em> Compare Prices</button>
                                                        </td>
                                                    <tr>
                                                `
                                            }
                                        }
                                    )
                                );
                                compQuotationHtml.html(`
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th colspan="3"><span class="badge bg-primary">${data.name}</span> - Available Items</th>
                                            </tr>
                                            <tr>
                                                <th style="width: 25%;">Item</th>
                                                <th style="width: 25%;">Brand & Model</th>
                                                <th style="width: 20%;">Price Offered</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${itemRows}
                                        </tbody>
                                    </table>
                                `);
                            }
                        })
                        .catch(err => {
                            compQuotationHtml.html(`<div class="fst-italic text-muted">Please select company with quotations!</div>`)
                        })
                });
                
                
            });
            async function showComparison(item_id) {
                await axios.get(getItemComparison + '/' + item_id)
                    .then((res) => {
                        let data = res.data;
                        
                        $('#compareItemModal').html(`Compare ${data.data[0].ppmp.item_detail.description}'s prices from other suppliers/companies`);
                        let comparisonContent = `
                            <table class="table table-sm table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Brand & Model Offered</th>
                                        <th>Offered Price/Unit</th>
                                        <th>Supplier/Company</th>
                                    </tr>
                                </thead>
                                <tbody>
                            `;
                        data.data.forEach(item => {
                            comparisonContent += `
                                <tr>
                                    <td>${item.ppmp.item_detail.description}</td>
                                    <td>${item.brand_and_model_offered}</td>
                                    <td>${item.offered_unit_price} / ${item.ppmp.item_detail.unit.uom}</td>
                                    <td>${item.quotation.company.name}</td>
                                </tr>
                            `;
                        });
                        comparisonContent += `
                                </tbody>
                            </table>
                        `;
                        $('#compare-modal').html(comparisonContent);
                        $('#compare-price-modal').modal('toggle');
                    });
            }
            async function toggleItem(e, itemId) {
                e = e || window.event;
                selectedItems.push(itemId);
                if (selectedItems.length > 0) {
                    $('#save-btn').attr('disabled', false);
                    $('#abc').attr('disabled', false);
                }
            }
            async function generateBac() {
                if (!doubleCheck) {
                    const a = alert("Double check your entries first. Press save again to proceed.");
                    doubleCheck = true;
                } else {
                    const a = confirm("Are you sure to submit this? You cannot redo this after submitting this.");
                    if (a) {
                        if (doubleCheck && selectedItems.length > 0 && $('#abc').val() !== "" && !isNaN($('#abc').val())) {
                            let payload = {
                                companyId: $('#company').val(),
                                items: selectedItems,
                                abcVal: $('#abc').val()
                            };
                            await axios.post(postBacReso, payload)
                                .then(res => window.location.reload())
                                .catch(err => alert(err.response.data.message ? err.response.data.message : "Something went wrong. Please reload the page."));
                        } else {
                            console.log("NO");
                        }
                    } else {
                        doubleCheck = false;
                    }
                }
            }
        </script>
    </x-slot>
</x-dashboard-layout>