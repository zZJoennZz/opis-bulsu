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
        <button class="btn btn-sm btn-secondary" onclick="Popup()"><em class="bi bi-printer-fill"></em> Print</button>
    </div>
    <x-breadcrumb :breadcrumb="$breadcrumb" />
    
    <div id="bac-reso-print">
        <div class="d-flex">
            <img src="{{ asset('img/bsu-small-logo.png') }}" alt="BSU Logo" class="mx-auto" width="100px" />
        </div>
        <div class="text-center">
            <div>Republic of the Philippines</div>
            <div>Bulacan State University</div>
            <div class="fw-bold">Bids and Awards Committee for Goods and Services</div>
            <div>City of Malolos, Bulacan</div>
            <div class="text-uppercase fw-bold">Abstract of Canvass and BAC Resolution</div>
            <div class="text-uppercase fw-bold">Resolution Recommending to Award of the Procurement of Medical & Dental Supply</div>
            <div class="text-uppercase fw-bold">For COVID-19 Preventive Measure Through Small Value Procurement-By Lot</div>
        </div>
        <div class="mb-3"><strong>ABC: ₱{{ number_format($bac_record->abc, 2) }}</strong> <span class="float-end">Date: {{ date('d') }} {{ date('M') }} {{ date('Y') }}</span></div>
        <table class="table table-sm table-bordered border-dark mb-5">
            <thead>
                <tr class="text-uppercase text-center">
                    <th style="width: 30%;">Name and Description of Articles Being Requisitioned</th>
                    <th style="width: 10%;">Approved Unit Price per Item</th>
                    <th style="width: 10%;">Quantity and Unit</th>
                    <th style="width: 10%;">Unit Price</th>
                    <th style="width: 20%;">Extended Amount</th>
                    <th style="width: 20%;">Supplier/Company Name</th>
                </tr>
            </thead>
            @php
                $extendedAmt = 0;
            @endphp
            @php
                $rowCtr = 1;
            @endphp
            @foreach ($bac_record->items as $item)
                <tr>
                    <td>{{ $item->quotation_item->pr_item->ppmp->item_detail->description }}</div>
                    <td>₱{{ number_format($item->quotation_item->offered_unit_price, 2) }}</td>
                    @php
                        $totalQty = 0;
                    @endphp
                    @foreach ($item->quotation_item->pr_item->ppmp->milestones as $milestone)
                        @php
                            $totalQty += $milestone->milestone_value
                        @endphp
                    @endforeach
                    
                    <td>{{ $totalQty }} {{ $item->quotation_item->pr_item->ppmp->item_detail->unit->uom }}</td>
                    <td>₱{{ number_format($item->quotation_item->pr_item->ppmp->item_detail->price_catalogue, 2) }}</td>
                    @php
                        $extendedAmt += $item->quotation_item->pr_item->ppmp->item_detail->price_catalogue * $totalQty;
                    @endphp
                    @if ($rowCtr === 1 || count($bac_record->items) === 1)
                        <td rowspan="{{count($bac_record->items)}}" class="text-center" style="vertical-align: middle;">₱{{ number_format($extendedAmt, 2) }}</td>
                        <td rowspan="{{count($bac_record->items)}}" class="text-center" style="vertical-align: middle;">{{ $bac_record->company->name }}</td>
                    @endif
                </tr>
                @php
                    $rowCtr += 1;
                @endphp
            @endforeach
        </table>
        <div class="mb-3">
            <strong>WHEREAS,</strong> the items to be procured are included in the Annual Procurement Plan for the year {{ $bac_record->year }} of the Bulacan State University with an Approved
            Budget for the Contract of <span class="fw-bold"><span class="text-uppercase">{{ translateToWords($bac_record->abc) }} (₱{{number_format($bac_record->abc, 2)}})</span></span>
        </div>
        <div class="mb-3">
            <strong>WHEREAS, Section 10</strong> of <strong>Republic Act No. 9184</strong> entitled "Government Procurement Reform Act" provides that all procurement shall be done through competitive bidding, except as provided for in Article XVI (Alternative Methods of Procurement) of the act, namely: Limited Source Bidding, Direct Contracting, Repeat Order, Shopping and Negotiated Procurement.
        </div>
        <div class="mb-1">
            <strong>WHEREAS, Section 53.9</strong> of the <strong>2016 Revised Implementing Rules and Regulations (IRR)</strong> of the said act provides that "where the procurement does not fall under shopping in Section 52 of the IRR and the amount involved does not exceed the thresholds prescribed in Annex "H" of the IRR:"
        </div>
        <div class="ps-5 mb-3">
            <div class="text-uppercase fw-bold">Thresholds for small value procurement:</div>
            <div class="ps-5">
                2. <strong>Small Value Procurement</strong> shall not exceed the following:
                <div class="ps-5">a.) For NGAs, GOCCS, GFIs, and SUCs, One Million Pesos (₱1,000,000.00)</div>
            </div>
        </div>
        <div class="mb-3">
            <strong>WHEREAS,</strong> the BAC decided to procure the said items on a lot basis; secured Request for Quotations and submitted their respective proposals to the Bids and Awards Committee, to wit:
            <ul>
                @foreach ($companies as $company)
                    <li>{{ $company->name }}</li>
                @endforeach
            </ul>
        </div>
        <div class="mb-3">
            <strong>WHEREAS, {{$bac_record->company->name}}</strong> submitted the <strong>lowest obtainable proposal</strong> items to be procured;
        </div>
        <div class="mb-3">
            <strong>NOW THEREFORE,</strong> after meticulous perusal, validation and verification, we, the members of Bids and Awards Committee hereby certify that the foregoing abstract of canvass is true and correct and that we have reviewed and evaluated the quotation and hereby recommend to the University President to procure the items from {{$bac_record->company->name}}. amounting to WINNING BID AMOUNT (bid amount);
        </div>
        <div class="mb-3">
            <strong>RESOLVED,</strong> at the Bulacan State University, City of Malolos, Bulacan, this <strong>{{ ordinal(date('d')) }} of {{ date('M') }} {{ date('Y') }}</strong>
        </div>
        <table class="table table-bordered border-dark w-75 mx-auto mb-0">
            <tr class="small">
                <td style="width: 25%">
                    <div class="text-center">
                        <div class="fw-bold">{{ getSettingValue('bac_chairman') }}</div>
                        Chair
                    </div>
                </td>
                <td style="width: 25%">
                    <div class="text-center">
                        <div class="fw-bold">{{ getSettingValue('vice_chair_1') }}</div>
                        Vice Chair
                    </div>
                </td>
                <td style="width: 25%">
                    <div class="text-center">
                        <div class="fw-bold">{{ getSettingValue('vice_chair_2') }}</div>
                        Vice Chair
                    </div>
                </td>
                <td style="width: 25%">
                    <div class="text-center">
                        <div class="fw-bold pt-1">_____________</div>
                        End User
                    </div>
                </td>
            </tr>
        </table>
        <table class="table table-bordered border-dark w-50 mx-auto">
            <tr class="small">
                <td style="width: 33.33%">
                    <div class="text-center">
                        <div class="fw-bold">{{ getSettingValue('member_1') }}</div>
                        Member
                    </div>
                </td>
                <td style="width: 33.33%">
                    <div class="text-center">
                        <div class="fw-bold">{{ getSettingValue('member_2') }}</div>
                        Member
                    </div>
                </td>
                <td style="width: 33.33%">
                    <div class="text-center">
                        <div class="fw-bold">{{ getSettingValue('member_3') }}</div>
                        Member
                    </div>
                </td>
            </tr>
        </table>
        <div class="border border-dark py-5 text-center">
            <div class="small mb-4">Approved:</div>
            <div class="fw-bold">{{ getSettingValue('university_president') }}</div>
            <div>President</div>
        </div>
    </div>
    <x-slot:additional_script>
        <script>
            function Popup() {
                let data = $('#bac-reso-print').html();
                var mywindow = window.open('', 'new div', 'height=400,width=600');
                mywindow.document.write('<html><head><title></title>');
                mywindow.document.write('<link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" type="text/css" />');
                mywindow.document.write('<style>@media print { html,body {width:297mm;height:210mm} }</style>');
                mywindow.document.write('</head><body >');
                mywindow.document.write(data);
                mywindow.document.write('</body></html>');
                mywindow.document.close();
                mywindow.focus();
                setTimeout(function(){mywindow.print();},1000);

                return true;
            }
        </script>
    </x-slot>
</x-dashboard-layout>