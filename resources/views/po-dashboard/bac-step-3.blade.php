<x-dashboard-layout>
    <x-slot:title>
        Prepare BAC Step 3
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'BAC Resolution', 'route' => 'bac-reso.all'],
            ['name' => 'Prepare BAC']
        ]
    @endphp
    
    <div class="float-end">
        <a href="{{route('bac-reso.add')}}?step=1" class="btn btn-secondary btn-sm"><em class="bi bi-arrow-clockwise"></em> Reset</a>
    </div>
    <x-breadcrumb :breadcrumb="$breadcrumb" />
    
    <div>
        <div>
            {{-- @if (count($company_quotations) === 0) --}}
            @if ($company_quotations === null)
                <div class="alert alert-warning" role="alert">
                    <em class="bi bi-exclamation-diamond-fill"></em> No items available or already have BAC.
                </div>
            @else
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
                <div class="table-responsive">
                    <table id="available-items-for-bac" class="table table-bordered table-sm border-dark caption-top" style="min-width: 500px;">
                        <caption><span class="badge bg-primary">{{$company_quotations->name}}</span> - Available Items</caption>
                        <thead>
                            <tr>
                                <th>Add</th>
                                <th style="width: 30%">Item</th>
                                <th style="width: 20%">Brand and Model Offered</th>
                                <th style="width: 15%">Purchase Request #</th>
                                <th style="width: 20%">Offered Unit Price per Unit</th>
                                <th></th>
                            </tr>
                        </thead>
                        @foreach ($company_quotations->quotations as $quotation)
                            @foreach ($quotation->items as $item)
                                @if ($item->pr_item !== null && $item->pr_item->ppmp->item_detail !== null)
                                    <tr>
                                        <td class="bg-dark">
                                            <div class="form-check form-switch mx-auto">
                                                <label class="form-check-label" for="item{{$item->id}}">
                                                    <input class="form-check-input cbItem" type="checkbox" role="switch" id="item{{$item->id}}" value="{{$item->id}}">
                                                </label>
                                            </div>
                                        </td>
                                        <td>{{ $item->pr_item->ppmp->item_detail->description }}</td>
                                        <td>{{ $item->brand_and_model_offered }}</td>
                                        <td>{{ $item->pr_item->pr->pr_number }}</td>
                                        <td>₱{{ number_format($item->offered_unit_price, 2) }} / {{$item->pr_item->ppmp->item_detail->unit->uom}}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" onclick="showComparison({{$item->pr_item->id}})">
                                                <em class="bi bi-table"></em> Compare
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endforeach
                    </table>
                    <label class="form-label" for="enter-abc">Approved Budget Contract</label>
                    <span class="text-muted small fst-italic">Enter approved budget contract for this supplier/company for the selected items above</span>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="peso-icon">₱</span>
                        <input id="enter-abc" name="enter-abc" type="text" class="form-control" placeholder="Enter Approved Budget Contract" aria-label="Enter ABC" aria-describedby="enter-abc">
                    </div>
                    <div class="text-end">
                        <button type="button" id="submit-bac-btn" class="btn btn-primary">Generate BAC <em class="bi bi-chevron-double-right"></em></button>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="confirmGenerateBac" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="confirmGenerateBacLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-title fs-5 fw-bold text-secondary" id="confirmGenerateBacLabel"><span class="badge bg-warning">Warning</span></div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="d-flex justify-content-center">
                                <script defer src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                                <lottie-player src="https://assets7.lottiefiles.com/packages/lf20_OcScBXmv1l.json"  background="transparent"  speed="1"  style="width: 100px; height: 100px;"  loop  autoplay></lottie-player>
                            </div>
                            <div class="text-center fw-bold">
                                Please confirm the selected items and ABC are correct.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary fw-bold" onclick="generateBac()">Confirm</button>
                        </div>
                        </div>
                    </div>
                </div>

                <x-slot:additional_script>
                    <script src="{{ asset('build/assets/app.b487754a.js') }}"></script>
                    <script>
                        let submitBacBtn = $('#submit-bac-btn');
                        let getItemComparison = "{{route('quotation-comparison.single')}}";
                        let postBacReso = "{{route('bac-reso.perform')}}";
                        let selectedItems = [];

                        $('document').ready(function () {
                            $('#submit-bac-btn').on('click', function (e) {
                                let selectedItems = [];
                                const allCheckboxes = $('.cbItem');
                                const abcVal = $('#enter-abc').val();
                                for (let i = 0; i < allCheckboxes.length; i++) {
                                    if (allCheckboxes[i].checked) {
                                        selectedItems.push(allCheckboxes[i].value);
                                    }
                                }

                                if (selectedItems.length === 0) {
                                    alert('Please select items first.');
                                    return;
                                }
                                
                                if (abcVal === 0 || abcVal === null || abcVal === '' || abcVal === undefined || isNaN(parseFloat(abcVal))) {
                                    alert('Please enter ABC properly.');
                                    return;
                                }
                                $('#confirmGenerateBac').modal('toggle');
                            });
                        });

                        async function generateBac() {
                            const e = window.event;
                            e.target.disabled = true;
                            const oldContent = e.target.innerHTML;
                            e.target.innerHTML = `
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div> Loading
                            `;
                            const abcVal = $('#enter-abc').val();
                            const allCheckboxes = $('.cbItem');
                            let selectedItems = [];
                            for (let i = 0; i < allCheckboxes.length; i++) {
                                if (allCheckboxes[i].checked) {
                                    selectedItems.push(allCheckboxes[i].value);
                                }
                            }

                            if (selectedItems.length === 0) {
                                alert('Please select items first.');
                                return;
                            }

                            if (abcVal === 0 || abcVal === null || abcVal === '' || abcVal === undefined || isNaN(parseFloat(abcVal))) {
                                alert('Please enter ABC properly.');
                                return;
                            }
                            
                            let payload = {
                                companyId: '{{$company_quotations->id}}',
                                items: selectedItems,
                                abcVal
                            };
                            await axios.post(postBacReso, payload)
                                .then(res => window.location.reload())
                                .catch(err => alert(err.response.data.message ? err.response.data.message : "Something went wrong. Please reload the page."));

                            e.target.disabled = false;
                            e.target.innerHTML = oldContent;
                        }

                        async function showComparison(item_id) {
                            let currBtn = window.event;
                            currBtn.target.disabled = true;
                            const oldContent = currBtn.target.innerHTML;
                            currBtn.target.innerHTML = `
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div> Loading
                            `;

                            try {
                                await axios.get(getItemComparison + '/' + item_id)
                                .then((res) => {
                                    let data = res.data;
                                    console.log(data);
                                    $('#compareItemModal').html(`Compare <span class="badge bg-primary">${data.data[0].pr_item.ppmp.item_detail.description}'s</span> prices from other suppliers/companies`);
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
                                                <td>${item.pr_item.ppmp.item_detail.description}</td>
                                                <td>${item.brand_and_model_offered}</td>
                                                <td>${item.offered_unit_price} / ${item.pr_item.ppmp.item_detail.unit.uom}</td>
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
                            } catch (err) {
                                console.log(err);
                            }

                            currBtn.target.innerHTML = oldContent;
                            currBtn.target.disabled = false;
                        }
                    </script>
                    @include('layout/datatable', ['tableId' => 'available-items-for-bac'])
                </x-slot>
            @endif
        </div>
    </div>
</x-dashboard-layout>