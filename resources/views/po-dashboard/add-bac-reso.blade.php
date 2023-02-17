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
            <div id="comp-quotations">
                <div class="fst-italic text-muted">Please select company with quotations!</div>
            </div>
        </div>
    </div>

    <script>
        let getCompanyQuotationUrl = "{{route('company-quotation.single')}}";
        $('document').ready(function() {
            $('#company').on('change', async function() {
                let compQuotationHtml = $('#comp-quotations');
                compQuotationHtml.html(`
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `);
                await fetch(getCompanyQuotationUrl + '/' + this.value)
                    .then(async (res) => {
                        if (res.status !== 200) {
                            console.log("Something went wrong. Company don't have quotation or runtime error.");
                            compQuotationHtml.html(`<div class="fst-italic text-muted">Please select company with quotations!</div>`)
                        } else {
                            let data = await res.json();
                            data = data.data[0];

                            let itemRows = ``;
                            data.quotations.map(quote =>
                                quote.items.map(item =>
                                    itemRows += `
                                        <tr>
                                            <td>${item.ppmp.item_detail.description}</td>
                                            <td>${item.offered_unit_price}</td>
                                            <td class="d-flex justify-content-center">
                                                <button class="btn btn-primary btn-sm me-2"><em class="bi bi-plus"></em> Add</button> <button class="btn btn-secondary btn-sm"><em class="bi bi-table"></em> Compare</button>
                                            </td>
                                        <tr>
                                    `
                                )
                            );
                            compQuotationHtml.html(`
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th colspan="3"><span class="badge bg-primary">${data.name}</span> - Available Items</th>
                                        </tr>
                                        <tr>
                                            <th style="width: 50%;">Item</th>
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
                        console.log(err);
                        compQuotationHtml.html(`<div class="fst-italic text-muted">Please select company with quotations!</div>`)
                    })
            });
        });
    </script>

</x-dashboard-layout>