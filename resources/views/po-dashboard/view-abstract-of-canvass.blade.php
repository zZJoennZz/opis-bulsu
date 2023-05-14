<x-dashboard-layout>
    <x-slot:title>
        {{ $bac_reso->abstract_of_canvass->pr->pr_number }} Abstract of Canvass
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>'],
            ['name' => 'Abstract of Canvasses <span class="badge bg-primary">'. Auth::user()->ppmp_year .'</span>', 'route' => 'aoc.all'],
            ['name' => 'View ' . $bac_reso->abstract_of_canvass->pr->pr_number . ' Abstract of Canvass'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />

    <div class="modal fade" id="compareItemPrice" tabindex="-1" aria-labelledby="compareItemPriceLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="h1 modal-title fs-5" id="compareItemPriceLabel">Compare <span class="badge bg-primary" id="modalTitle">#</span> prices</div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm table-bordered border-dark">
                        <thead>
                            <th>Company</th>
                            <th>Item Number</th>
                            <th>Brand / Model</th>
                            <th>Offered Unit Price</th>
                            <th>Select</th>
                        </thead>
                        <tbody id="compare-table">

                        </tbody>
                    </table>
                </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div> --}}
            </div>
        </div>
    </div>
    @if ($bac_reso->is_draft === 1)
        @if ($bac_reso->abstract_of_canvass->type === "BY_ITEM")
            <div class="mb-3">
                <div class="border p-2 border-secondary">
                    <div class="float-end">
                        <span class="badge bg-primary text-uppercase">{{ $bac_reso->abstract_of_canvass->type === "BY_ITEM" ? "By item" : "By lot" }}</span>
                    </div>
                    <div class="mb-1">
                        <small class="text-uppercase fw-bold text-secondary">Information:</small>
                    </div>
                    <div class="mb-1">
                        <strong>PR #:</strong> <span class="badge bg-primary">{{ $bac_reso->abstract_of_canvass->pr->pr_number }}</span>
                    </div>
                    <div class="mb-1">
                        <strong>Purpose:</strong> {{ $bac_reso->abstract_of_canvass->pr->purpose }}
                    </div>
                    <div>
                        <strong>ABC:</strong> ₱ {{ number_format($bac_reso->abstract_of_canvass->abc, 2) }} <span class="text-uppercase">({{ trim(translateToWords($bac_reso->abstract_of_canvass->abc)) }})</span>
                    </div>
                </div>
            </div>
            <div class="mb-3 mt-3">
                <div class="fs-4 fw-bold text-secondary">Select supplier / dealer per item</div>
            </div>
            <div>
                <table class="table table-sm table-hover border-dark caption-top">
                    <caption>
                        
                    </caption>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Unit</th>
                            <th>Qty.</th>
                            <th>Unit Price</th>
                            <th>Extended Amount</th>
                            <th class="text-end">Suppliers / Dealers</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bac_reso->abstract_of_canvass->pr->pr_items as $item)
                            @php
                                $isViable = 0;
                                $companyName = "";
                                $bacResoItemId = 0;
                                foreach ($item->quotations as $quote) {
                                    if ($quote->bac_reso_item !== null) {
                                        $isViable += 1;
                                        $companyName = $quote->quotation->company->name;
                                        $bacResoItemId = $quote->bac_reso_item->id;
                                    }
                                }
                            @endphp
                            <tr>
                                <td>{{ $item->ppmp->item_detail->description }}</td>
                                <td>{{ $item->ppmp->item_detail->unit->uom }}</td>
                                @php
                                    $itemQty = 0;
                                    foreach ($item->ppmp->milestones as $m) {
                                        $itemQty += $m->milestone_value;
                                    }
                                @endphp
                                <td>{{ $itemQty }}</td>
                                <td>₱ {{ number_format($item->ppmp->item_detail->price_catalogue, 2) }}</td>
                                <td>₱ {{ number_format($item->ppmp->item_detail->price_catalogue * $itemQty, 2) }}</td>
                                <td class="text-end">
                                    @if ($isViable === 0)
                                        <button class="btn btn-sm btn-primary" type="button" onclick="getComparison({{$item->id}}, '{{$item->ppmp->item_detail->description}}')"><em class="bi bi-cash-stack"></em> View Quotations</button>
                                    @else
                                        {{ $companyName }}
                                        <form action="{{ route('bac-reso.delete', ['bac_reso_item_id' => $bacResoItemId]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit"><em class="bi bi-x"></em></button>
                                        </form>
                                    @endif
                                    
                                    {{-- <a href="{{ route('bac-reso.compare', ['pr_item_id' => $item->id]) }}" target="_blank" class="btn btn-secondary">Test Me</a> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="border-bottom p-2 border-secondary mb-3">
                <div class="float-end">
                    <span class="badge bg-primary text-uppercase">{{ $bac_reso->type === "BY_ITEM" ? "By item" : "By lot" }}</span>
                </div>
                <div class="mb-1">
                    <small class="text-uppercase fw-bold text-secondary">Information:</small>
                </div>
                <div class="mb-1">
                    <strong>PR #:</strong> <span class="badge bg-primary">{{ $bac_reso->abstract_of_canvass->pr->pr_number }}</span>
                </div>
                <div class="mb-1">
                    <strong>Purpose:</strong> {{ $bac_reso->abstract_of_canvass->purpose }}
                </div>
                <div>
                    <strong>ABC:</strong> ₱ {{ number_format($bac_reso->abstract_of_canvass->abc, 2) }} (<span class="text-uppercase">{{ translateToWords($bac_reso->abstract_of_canvass->abc) }}</span> )
                </div>
            </div>
            <div class="table-responsive border-secondary border p-2 mb-3">
                <table class="table table-bordered table-sm border-dark caption-top">
                    <caption>
                        
                    </caption>
                    <thead class="text-uppercase text-center align-middle">
                        <tr>
                            <th scope="col" style="min-width: 400px;" rowspan="3">Item</th>
                            <th scope="col" style="width: 80px;" rowspan="3">Unit</th>
                            <th scope="col" style="width: 80px;" rowspan="3">Qty.</th>
                            <th scope="col" style="width: 130px;" rowspan="3">Unit Price</th>
                            <th scope="col" style="width: 130px;" rowspan="3">Extended Amount</th>
                            <th scope="col" style="min-width: 50%;" colspan="{{ count($companies) * 3 }}">Name of the Bidders / Dealers</th>
                        </tr>
                        <tr>
                            @foreach ($companies as $c)
                                <th scope="col" colspan="3" class="text-primary">{{ $c->name }}</th>
                            @endforeach
                        </tr>
                        <tr>
                            @for ($i = 0; $i < count($companies); $i++)
                                <th scope="col" style="width: 70px; font-size: 12px">Unit Price</th>
                                <th scope="col" style="width: 70px; font-size: 12px">Brand</th>
                                <th scope="col" style="width: 100px; font-size: 12px">Extended Amount</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bac_reso->abstract_of_canvass->pr->pr_items as $item)
                            <tr>
                                <td>{{ $item->ppmp->item_detail->description }}</td>
                                <td>{{ $item->ppmp->item_detail->unit->uom }}</td>
                                {{-- COMPUTING THE QTY --}}
                                @php
                                    $itemQty = 0;
                                @endphp
                                @foreach ($item->ppmp->milestones as $m)
                                    @php
                                        $itemQty += $m->milestone_value;
                                    @endphp
                                @endforeach
                                <td>{{ $itemQty }}</td>
                                <td>₱ <div class="float-end">{{ number_format($item->ppmp->item_detail->price_catalogue, 2) }}</div></td>
                                <td>₱ <div class="float-end">{{ number_format($item->ppmp->item_detail->price_catalogue * $itemQty, 2) }}</div></td>
                
                                {{-- LISTING COMPANY QUOTATIONS --}}
                                @foreach ($companies as $c)
                                    @php
                                        $itemsFound = 0;
                                    @endphp
                                    @foreach ($c->quotations as $q)
                                        @foreach ($q->items as $i)
                                            @if ($i->pr_item->id === $item->id)
                                                <td>₱ <div class="float-end">{{ number_format($i->offered_unit_price, 2) }}</td>
                                                <td>{{ $i->brand_and_model_offered }}</td>
                                                <td>₱ <div class="float-end">{{ number_format($i->offered_unit_price * $itemQty, 2) }}</div></td>
                                                @php
                                                    $itemsFound += 1;
                                                @endphp
                                            @endif
                                        @endforeach
                                    @endforeach
                                    @if ($itemsFound === 0)
                                        <td class="text-center" style="font-size: 11px;">N/A</td>
                                        <td class="text-center" style="font-size: 11px;">N/A</td>
                                        <td class="text-center" style="font-size: 11px;">N/A</td>
                                    @endif
                                @endforeach
                                @php
                                    $itemQty = 0;
                                @endphp
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-end fw-bold text-uppercase">Select supplier</td>
                            @foreach ($companies as $c)
                                @php
                                    $itemsFound = 0;
                                @endphp
                                @foreach ($c->quotations as $q)
                                    @foreach ($q->items as $i)
                                        @php
                                            if ($i->pr_item->pr->id === $bac_reso->abstract_of_canvass->pr->id) {
                                                $itemsFound += 1;
                                            }
                                        @endphp
                                    @endforeach
                                @endforeach
                                {{-- {{ $itemsFound . '|' . count($bac_reso->abstract_of_canvass->pr->pr_items) }} --}}
                                @if ($itemsFound !== count($bac_reso->abstract_of_canvass->pr->pr_items))
                                    <td class="text-center" colspan="3" style="font-size: 11px;">
                                        Does not meet 'by lot' requirement.
                                    </td>
                                @else
                                    <td class="text-center" colspan="3">
                                        @if (count($sel_company) > 0)
                                            @if ($sel_company[0]->id === $c->id)
                                                <span class="badge rounded-pill fs-6 bg-success text-uppercase"><em class="bi bi-check-circle-fill"></em> Selected</span>
                                                <form method="POST" action="{{ route('bac-reso.delete-batch') }}" class="d-inline">
                                                    @csrf
                                                    @method("DELETE")
                                                    <input type="hidden" name="b_a_c_resos_id" id="b_a_c_resos_id" value="{{ $bac_reso->id }}">
                                                    <button class="badge bg-danger rounded-pill fs-6" style="border: none;"><em class="bi bi-x"></em></button>
                                                </form>
                                            @else
                                                <span class="badge rounded-pill fs-6 bg-secondary text-uppercase"><em class="bi bi-x-circle-fill"></em> Not selected</span>
                                            @endif
                                        @else
                                            <form action="{{ route('bac-reso-item.new') }}"  method="POST">
                                                @csrf
                                                <input type="hidden" value="{{ $bac_reso->id }}" name="bacId" id="bacId{{$c->id}}">
                                                <input type="hidden" value="{{ $c->id }}" name="company" id="company{{$c->id}}">
                                                <button type="submit" class="btn btn-primary w-100 fw-bold text-uppercase"><em class="bi bi-bag-check-fill"></em> Select</button>
                                            </form>
                                        @endif
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
        <div>
            <form method="POST" action="{{ route('aoc.complete', ["id" => $bac_reso->abstract_of_canvass->id]) }}" onsubmit="return confirm('This will be final and cannot be reversed. Are you sure?')">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="abc" class="form-label">ABC</label>
                        <input type="number" value="{{ $bac_reso->abstract_of_canvass->abc }}" class="form-control" id="abc" name="abc" title="number" placeholder="Type amount" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-12 col-md-6 col-md-6">
                        <label for="bac_chairman" class="form-label">BAC Chairman</label>
                        <input type="text" class="form-control" value="{{ $bac_reso->abstract_of_canvass->bac_chairman }}" id="bac_chairman" name="bac_chairman" required>
                    </div>
                    <div class="col-sm-12 col-md-6 col-md-6">
                        <label for="vice_chairman" class="form-label">Vice Chairman</label>
                        <input type="text" class="form-control" id="vice_chairman" name="vice_chairman" value="{{ $bac_reso->abstract_of_canvass->vice_chairman }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-12 col-md-6 col-md-6">
                        <label for="member_1" class="form-label">Member</label>
                        <input type="text" class="form-control" id="member_1" name="member_1" value="{{ $bac_reso->abstract_of_canvass->member_1 }}" required>
                    </div>
                    <div class="col-sm-12 col-md-6 col-md-6">
                        <label for="member_2" class="form-label">Member</label>
                        <input type="text" class="form-control" id="member_2" name="member_2" value="{{ $bac_reso->abstract_of_canvass->member_2 }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-12 col-md-6 col-md-6">
                        <label for="member_3" class="form-label">Member</label>
                        <input type="text" class="form-control" id="member_3" name="member_3" value="{{ $bac_reso->abstract_of_canvass->member_3 }}" required>
                    </div>
                    <div class="col-sm-12 col-md-6 col-md-6">
                        <label for="member_4" class="form-label">Member</label>
                        <input type="text" class="form-control" id="member_4" name="member_4" value="{{ $bac_reso->abstract_of_canvass->member_4 }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-12 col-md-6 col-md-6">
                        <label for="technical_resource_person" class="form-label">Technical Resource Person</label>
                        <input type="text" class="form-control" id="technical_resource_person" name="technical_resource_person" value="{{ $bac_reso->abstract_of_canvass->technical_resource_person }}" required>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <label for="procurement_office_rep" class="form-label">Procurement Office's Representative</label>
                        <input type="text" class="form-control" id="procurement_office_rep" name="procurement_office_rep" value="{{ $bac_reso->abstract_of_canvass->procurement_office_rep }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    
                    <div class="col-sm-12 col-md-6">
                        <label for="president" class="form-label">University President</label>
                        <input type="text" class="form-control" id="president" name="president" value="{{ $bac_reso->abstract_of_canvass->president }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">Complete <em class="bi bi-chevron-double-right"></em></button>
                    </div>
                </div>
            </form>
        </div>
    @endif
    <x-slot:additional_script>
        @vite('resources/js/app.js')
        <script>
            async function getComparison(pr_item_id, item_name) {
                let btnState = window.event.target
                btnState.disabled = true;
                $('#modalTitle').html(item_name);
                await axios.get(`{{ route('bac-reso.compare') }}/${pr_item_id}`)
                    .then((res) => {
                        const data = res.data.data;
                        let tblContent = ``;
                        data.map((item) => {
                            tblContent += `
                                <tr>
                                    <td>${item.quotation.company.name}</td>
                                    <td>${item.item_number}</td>
                                    <td>${item.brand_and_model_offered}</td>
                                    <td>${item.offered_unit_price}</td>
                                    <td>
                                        <form method="POST" action="{{ route('bac-reso-item.new') }}">
                                            <input type="hidden" value="${item.id}" name="item" id="item${item.id}">
                                            <input type="hidden" value="{{ $bac_reso->id }}" name="bacId" id="bacId${item.id}">
                                            @csrf
                                            <button class="btn btn-sm btn-primary" type="submit">Select</button>
                                        </form>
                                    </td>
                                </tr>
                            `;
                        })
                        $('#compare-table').html(tblContent);
                        $('#compareItemPrice').modal('show');
                        btnState.disabled = false;
                    })
                    .catch((err) => {
                        if (err.response.status === 404) {
                            alert('Item not found!');
                        } else {
                            alert('Something went wrong. Cannot fetch data.');
                        }
                        btnState.disabled = false;
                    });
            }
        </script>
    </x-slot>
</x-dashboard-layout>