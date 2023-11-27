<x-dashboard-layout>
    <x-slot:title>
        Add New Quotation
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Price Quotations', 'route' => 'quotation.all'],
            ['name' => 'Add New Quotation'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    <h1 class="h5 text-uppercase text-center text-secondary mb-4">Request for Quotation</h1>
    <div class="row mb-3">
        <div class="col-sm-12 col-md-6 border-end border-secondary">
            <div class="mb-3">
                <label for="companies_id" class="form-label">Company</label>
                <select onchange="get_company_details(event)" id="companies_id" name="companies_id" class="form-select" aria-label="Company name" required>
                    <option selected hidden disabled value="">Select company</option>
                    @foreach ($company_profiles as $profile)
                        <option value="{{$profile->id}}">{{$profile->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="purchase_requests_id" class="form-label">Purchase Request #</label>
                <select onchange="get_pr_record(event)" id="purchase_requests_id" name="purchase_requests_id" class="form-select" aria-label="Purchase request number">
                    <option value="" disabled hidden selected>Select purchase request</option>
                    @foreach ($purchase_requests as $pr)
                        <option value="{{$pr->id}}">{{$pr->pr_number}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <h2 class="h6 fst-italic text-secondary">Company Profile</h2>
            <div id="company-details">
                <div class="opacity-50"><span class="placeholder col-10"></span></div>
                <div class="mb-3 opacity-50"><span class="placeholder col-12"></span></div>
                <div class="opacity-50"><span class="placeholder col-10"></span></div>
                <div class="mb-3 opacity-50"><span class="placeholder col-12"></span></div>
                <div class="opacity-50"><span class="placeholder col-10"></span></div>
                <div class="mb-3 opacity-50"><span class="placeholder col-12"></span></div>
                <div class="opacity-50"><span class="placeholder col-10"></span></div>
                <div class="mb-3 opacity-50"><span class="placeholder col-12"></span></div>
            </div>
        </div>
    </div>
    <hr />
    <div class="row mb-3">
        <div class="col-12">
            <h2 class="h6 fst-italic text-secondary">Purchase Request</h2>
            <div class="small text-muted">Items</div>
            <div>
                <table id="pr-items-table" class="table table-sm table-striped">
                    <caption>List of PR items</caption>
                    <thead>
                        <tr>
                            <th style="width: 50%;">Item Detail</th>
                            <th style="width: 40%;">Quantity</th>
                            <th>Add</th>
                        </tr>
                    </thead>
                    <tbody id="pr-item-details">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <hr />
    <div class="row mb-3">
        <div class="col-12">
            <h2 class="h6 fst-italic text-secondary">Quotation</h2>
            <div class="small text-muted">Items</div>
            <div class="table-responsive">
                <table id="quote-items-table" class="table table-striped">
                    <caption>List of request for quotation</caption>
                    <thead>
                        <tr>
                            <th style="width: 25%;">Item Detail</th>
                            <th style="width: 10%;">Quantity</th>
                            <th style="width: 15%;">Item No.</th>
                            <th style="width: 20%;">Brand & Model Offered</th>
                            <th style="width: 20%;">Offered Unit Price</th>
                            <th style="width: 10%;">Total Price</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody id="quote-item-details">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <button type="button" onclick="submitRequest()" class="btn btn-primary">
                <em class="bi bi-file-earmark-break-fill"></em> Submit
            </button>
        </div>
    </div>
    <x-slot:additional_script>
        <script src="{{ asset('build/assets/app.b487754a.js') }}"></script>
        <script>
            const qnew = "{{ route('quotation.new') }}";
            const qnewRed = "{{ route('quotation.all') }}";
            const comSingApi = "{{ route('company.single.api') }}";
            const prSingQApi = "{{ route('pr-single-quotation.api') }}";
        
            let itemsMaster = [];
            let prItems = [];
            let quoteItems = [];
            let selectedItems = [];
        
            async function submitRequest() {
                const confirmSubmission = confirm("Are you sure to submit this request?");
                if (confirmSubmission) {
                    if ($('#companies_id').val() !== "Select company" && $('#purchase_requests_id').val() !== 'Select purchase request') {
                        if (selectedItems.length > 0) {
                            itemData = [];
        
                            selectedItems.map(id => {
                                itemData.push({
                                    'purchase_requests_id': id,
                                    'item_number': $(`#item_number_${id}`).val(),
                                    'brand_and_model_offered': $(`#brand_and_model_offered_${id}`).val(),
                                    'offered_unit_price': $(`#offered_unit_price_${id}`).val(),
                                });
                            });
        
                            let data =  {
                                'companies_id': $('#companies_id').val(),
                                'items': itemData,
                            };
        
                            await axios.post(qnew, data)
                                .then(res => {
                                    window.location.href = qnewRed;
                                })
                                .catch(err => alert('Quotation submission failed. Please try again.'));
                        } else {
                            alert('Please add items you want to submit for request.');
                            return false
                        }
                    } else {
                        alert('Please complete the form and select the valid values.');
                        return false;
                    }
                }
                return false;
            }
        
            function addToQuote(id) {
                selectedItems.push(id);
                prItems = itemsMaster.filter(d => !selectedItems.includes(d.id));
                quoteItems = itemsMaster.filter(d => selectedItems.includes(d.id));
        
                mapPrItems();
                mapQuoteItems();
            }
        
            function removeFromQuote(id) {
                selectedItems = selectedItems.filter(d => d != id);
        
                prItems = itemsMaster.filter(d => !selectedItems.includes(d.id));
                quoteItems = itemsMaster.filter(d => selectedItems.includes(d.id));
        
                mapPrItems();
                mapQuoteItems();
            }
        
            function mapPrItems() {
                let htmlContent = '';
        
                if (prItems.length >= 1) {
                    prItems.map(d => {
                    let totalQty = 0;
        
                    d.ppmp.milestones.map(d => totalQty += d.milestone_value);
        
                    htmlContent += `
                            <tr>
                                <td>${d.ppmp.item_detail.description}</td>
                                <td>${totalQty} ${d.ppmp.item_detail.unit.uom}</td>
                                <td><button class="btn btn-sm btn-primary" onclick="addToQuote(${d.id})"><em class="bi bi-plus-circle"></em></button></td>
                            </tr>
                        `
                    })
                    
                } else {
                    htmlContent = '<tr><td colspan="3" class="text-center">No data available in table</td></tr>';
                }
                $('#pr-item-details').html(htmlContent);
            }
        
            function mapQuoteItems() {
                let htmlContent = '';
        
                if (quoteItems.length >= 1) {
                    quoteItems.map(d => {
                    let totalQty = 0;
        
                    d.ppmp.milestones.map(d => totalQty += d.milestone_value);
        
                    htmlContent += `
                            <tr>
                                <td>
                                    <div>
                                        ${d.ppmp.item_detail.description}
                                    </div>
                                    <div class="small fst-italic">Price Catalogue: ${convertToCurrency(d.ppmp.item_detail.price_catalogue)}</div>
                                </td>
                                <td>${totalQty} ${d.ppmp.item_detail.unit.uom}</td>
                                <td><input id="item_number_${d.id}" name="item_number_${d.id}" value="${d.item_number}" class="form-control" readonly></td>
                                <td><input id="brand_and_model_offered_${d.id}" name="brand_and_model_offered_${d.id}" class="form-control"></td>
                                <td><input onchange="$('#total_${d.id}').val(${totalQty} * this.value)" id="offered_unit_price_${d.id}" name="offered_unit_price_${d.id}" class="form-control"></td>
                                <td><input class="form-control" id="total_${d.id}" disabled></td>
                                <td>
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                        <button class="btn btn-sm btn-danger" onclick="removeFromQuote(${d.id})"><em class="bi bi-x-circle"></em></button>
                                    </div>
                                </td>
                            </tr>
                        `
                    })
                    
                } else {
                    htmlContent = '<tr><td colspan="7" class="text-center">No data available in table</td></tr>';
                }
                $('#quote-item-details').html(htmlContent);
            }
        
            async function get_company_details(event) {
                prItems = [];
                quoteItems = [];
                selectedItems = [];
                mapPrItems();
                mapQuoteItems();
                $('#purchase_requests_id').val('Select purchase request');
                await axios.get(`${comSingApi}/${event.target.value}`)
                    .then(res => {
                        let companyDetails = res.data.data[0];
                        let htmlContent = `
                            <div class="table-responsive mt-3">
                                <table class="table table-sm table-bordered">
                                    <tr><th style="width: 20%;">Company</th><td styke="width: 80%;">${companyDetails.name}</td></tr>
                                    <tr><th>Address</th><td>${companyDetails.full_address}</td></tr>
                                    <tr><th>Contact #</th><td>${companyDetails.contact_number}</td></tr>
                                    <tr><th>TIN #</th><td>${companyDetails.tin}</td></tr>
                                    <tr><th>Email Address</th><td>${companyDetails.email_address}</td></tr>
                                </table>
                            </div>
                        `;
                        $('#company-details').html(htmlContent);
                    })
                    .catch(err => {
                        alert(`Cannot fetch company profile. Please reload the page.`)
                        $('#company-details').html(``);
                    });
            }
        
            async function get_pr_record(event) {
                let companyId = $('#companies_id').val();
                if (companyId === '' || companyId === null || companyId === undefined || companyId === 0 || companyId === "Select company") {
                    alert('Please select a company first.');
                    event.target.value = "Select purchase request";
                }
                await axios.get(`${prSingQApi}/${event.target.value}/${companyId}`)
                    .then(res => {
                        prItems = res.data[0].pr_items;
                        itemsMaster = prItems;
                        quoteItems = [];
                        mapPrItems();
                        mapQuoteItems();
                    })
                    .catch(err => {
                        // alert(`Cannot fetch PR record. Please reload the page.`);
                        $('#quote-item-details').html(``);
                        $('#pr-item-details').html(``);
                    });
                
            }
        </script>
    </x-slot>
</x-dashboard-layout>