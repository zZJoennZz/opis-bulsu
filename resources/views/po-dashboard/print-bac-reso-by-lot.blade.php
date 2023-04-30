<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>
            Print BAC Resolution |  OPIS - BulSU e-PROCUREMENT
        </title>
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <link rel="manifest" href="{{ asset('site.webmanifest') }}" crossorigin="use-credentials" />
        <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <style>
            * {
                font-family: 'Book Antiqua', Times, serif;
                font-size: 12px;
            }

            @media print {
                @page {
                    size: A4 landscape;
                    margin: 10px 0px 0px 10px;
                    page-break-after: always;
                }
            }
        </style>
    </head>
    <body>
        <div class="to-print" id="print-abs">
            <div class="container-fluid" style="position: relative;">
                <div class="row">
                    <div class="col-12">
                        <div style="position: absolute; top: 0; right: 1rem;" class="text-end">
                            <div class="fw-bold">BAC-II Resolution No. {{ $bac_reso->b_a_c_reso_number }}</div>
                            <div class="fw-bold">P.R. No. {{ $bac_reso->abstract_of_canvass->pr->pr_number }}</div>
                        </div>
                    </div>
                    <div class="col-7 m-auto">
                        <img src="{{ asset('img/bsu-small-logo.png') }}" alt="BSU Logo" style="position: absolute; top: 0; left: 20vw; width: 75px;" />
                        <div class="text-center">
                            <div>Republic of the Philippines</div>
                            <div class="fw-bold">BULACAN STATE UNIVERSITY</div>
                            <div class="fw-bold text-uppercase"><span style="font-size: 14px;">B</span>ids and <span style="font-size: 14px;">A</span>wards <span style="font-size: 14px;">C</span>ommittee for <span style="font-size: 14px;">G</span>oods and <span style="font-size: 14px;">S</span>ervices</div>
                            <div class="fst-italic">City of Malolos, Bulacan</div>
                            <div class="fw-bold mt-5">ABSTRACT OF CANVASS AND BAC RESOLUTION</div>
                            <div class="fw-bold mt-2">{{ $bac_reso->header_remarks }}</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="float-end fw-bold">BY LOT</div>
                        <div class="fw-bold">ABC: Php{{ number_format($bac_reso->abstract_of_canvass->abc, 2) }}</div>
                    </div>
                </div>
                
            @php
                $currCompany = 0;
                $currCompany1 = 0;
            @endphp
            @for ($i = 0; $i < count($companies)/3; $i++)
                @php
                    $companyId = [];
                    $compExtendedTotal = [0, 0, 0];
                @endphp
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-bordered border-dark table-sm">
                                <thead class="align-middle text-center">
                                    <tr>
                                        <th style="width: 5%;" rowspan="3">Item No.</th>
                                        <th style="width: 30%;" rowspan="3">NAME OF ARTICLES BEING REQUISITIONED</th>
                                        <th style="width: 5%;" rowspan="3">Approved unit price per item</th>
                                        <th style="width: 5%;" rowspan="3">Qty. and Units</th>
                                        <th style="width: 50%;" colspan="6">NAME OF BIDDERS</th>
                                    </tr>
                                    <tr style="color: #1f497d;">
                                        @for ($i1 = 0; $i1 < 3; $i1++)
                                            @if ($currCompany <= count($companies) - 1)
                                                <th colspan="2">{{ $companies[$currCompany]->name }}</th>
                                                @php
                                                    array_push($companyId, $currCompany);
                                                    $currCompany += 1;
                                                @endphp
                                            @else
                                                <th colspan="2"></th>
                                            @endif
                                        @endfor
                                    </tr>
                                    <tr>
                                        <th>Unit Price</th>
                                        <th>Extended Amount</th>
                                        <th>Unit Price</th>
                                        <th>Extended Amount</th>
                                        <th>Unit Price</th>
                                        <th>Extended Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $ctr = 1;
                                        $totalAmount = 0;
                                    @endphp
                                    @foreach ($bac_reso->abstract_of_canvass->pr->pr_items as $item)
                                        <tr>
                                            @php
                                                $itemQty = 0;
                                                foreach ($item->ppmp->milestones as $milestone) {
                                                    $itemQty += $milestone->milestone_value;
                                                }
                                            @endphp
                                            <td class="text-center">{{ $item->item_number }}</td>
                                            <td>{{ $item->ppmp->item_detail->description }}</td>
                                            <td class="text-end">{{ number_format($item->ppmp->item_detail->price_catalogue, 2) }}</td>
                                            <td class="text-center">{{ $itemQty }} {{ $item->ppmp->item_detail->unit->uom }}</td>
                                            @php
                                                $totalAmount += $item->ppmp->item_detail->price_catalogue * $itemQty;
                                            @endphp
                                            @php
                                                $totalCtr = 0;
                                            @endphp
                                            @foreach ($companyId as $cid)
                                                @php
                                                    $itemsFound = 0;
                                                @endphp
                                                @foreach ($companies[$cid]->quotations as $cq)
                                                    @foreach ($cq->items as $cqitem)
                                                        @if ($item->id === $cqitem->purchase_request_items_id)
                                                            @php
                                                                $selCompanyClass = "";
                                                            @endphp
                                                            @if (count($bac_reso->bac_reso_items->where('quotation_items_id', $cqitem->id)) === 1)
                                                                @php
                                                                    $selCompanyClass = "text-end fw-bold";
                                                                    $compExtendedTotal[$totalCtr] += $cqitem->offered_unit_price * $itemQty;
                                                                @endphp
                                                            @else
                                                                @php
                                                                    $selCompanyClass = "text-end";
                                                                @endphp
                                                            @endif
                                                            <td class="{{ $selCompanyClass }}">{{ number_format($cqitem->offered_unit_price, 2) }}</td>
                                                            <td class="{{ $selCompanyClass }}">{{ number_format($cqitem->offered_unit_price * $itemQty, 2) }}</td>
                                                            @php
                                                                $itemsFound += 1;
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                @endforeach

                                                @if ($itemsFound === 0)
                                                    <td class="text-center" style="font-size: 11px;">N/A</td>
                                                    <td class="text-center" style="font-size: 11px;">N/A</td>
                                                @endif
                                                @php
                                                    $totalCtr += 1;
                                                @endphp
                                            @endforeach
                                            @for ($i3 = 0; $i3 < 3 - count($companyId); $i3++)
                                                <td class="text-center" style="font-size: 11px;">N/A</td>
                                                <td class="text-center" style="font-size: 11px;">N/A</td>
                                            @endfor
                                            @php
                                                $ctr += 1;
                                            @endphp
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td class="text-light">-</td>
                                        <td class="small text-center fst-italic">*Nothing follows</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    {{-- @for ($i4 = 0; $i4 < 10 - count($bac_reso->abstract_of_canvass->pr->pr_items); $i4++)
                                        <tr>
                                            <td class="text-light">-</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endfor --}}
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold">

                                        <td></td>
                                        <td>TOTAL:</td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-end" colspan="2">{{ number_format($compExtendedTotal[0], 2) }}</td>
                                        <td class="text-end" colspan="2">{{ number_format($compExtendedTotal[1], 2) }}</td>
                                        <td class="text-end" colspan="2">{{ number_format($compExtendedTotal[2], 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    {{-- <div class="row">
                        <div class="col-12">
                            <span class="fw-bold">
                                Purpose: {{ $aoc[0]->pr->purpose }}
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 fw-bold">ABC: {{ number_format($aoc[0]->abc, 2) }}</div>
                        <div class="col-6 text-end fw-bold">PR NUMBER: {{ $aoc[0]->pr->pr_number }}</div>
                    </div> --}}

            @endfor
            
                <div class="row mb-2">
                    <div class="col-12" style="text-indent: 4rem;">
                        <span class="fw-bold">WHEREAS</span>, the goods to be procured are included in the Annual Procurement Plan for the year <span class="fw-bold">{{ $bac_reso->abstract_of_canvass->year }}</span> of the Bulacan State University with an Approved Budget for the Contract of <span class="fw-bold text-uppercase">{{ translateToWords($bac_reso->abstract_of_canvass->abc) }}</span> <span class="fw-bold">(Php{{ number_format($bac_reso->abstract_of_canvass->abc, 2) }});</span>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-12" style="text-indent: 4rem;">
                        <span class="fw-bold">WHEREAS, Sec. 10</span> of the <span class="fw-bold">Republic Act No. 9184</span> entitled “Government Procurement Reform Act” provides that all procurement shall be done through competitive bidding, except as provided for in Article XVI (Alternative Methods of Procurement) of the law which are Limited Source Bidding, Direct Contracting, Repeat Order, Shopping and Negotiated Procurement;
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-12" style="text-indent: 4rem;">
                        <span class="fw-bold">WHEREAS, Sec. 53.9</span> of the <span class="fw-bold">Implementing Rules and Regulations <span class="fst-italic">(IRR)</span></span> of the said law provides that where the procurement does not fall under Shopping in Section 52 of the IRR and the amount involved does not exceed the thresholds prescribed in Annex “H” of the IRR:
                    </div>
                </div>
                
                <div class="row mb-2">
                    <div class="col-12" style="text-indent: 8rem;">
                        <span class="fw-bold">THRESHOLDS FOR SMALL VALUE PROCUREMENT:</span>
                    </div>
                    <div class="col-12" style="text-indent: 12rem;">
                        2. <span class="fw-bold">Small Value Procurement</span> shall not exceed the following:
                    </div>
                    <div class="col-12" style="text-indent: 16rem;">
                        <span class="fst-italic">a.) For NGAs, GOCCs, GFIs, and SUCs, One Million Pesos (P 1, 000,000.00)</span>
                    </div>
                    <div class="col-12" style="text-indent: 12rem;">
                        <div class="fst-italic d-flex">
                            <div class="mr-5">x x x</div>
                            <div class="mr-5">x x x</div>
                            <div class="mr-5">x x x</div>
                        </div>
                    </div>
                </div>
                {{-- //TODO add the correct fields here. Also confirm where do we put the RFQ details. The place where the opening of quotations was conducted --}}
                <div class="row mb-2">
                    <div class="col-12" style="text-indent: 4rem;">
                        <span class="fw-bold">WHEREAS,</span> the University posted the Request for Quotations (RFQ) on the <span class="fw-bold">PhilGEPS</span> website with reference numbers <span class="fw-bold">{{ $bac_reso->rfq_reference_numbers }}</span> on <span class="fw-bold">{{ date('d F Y', strtotime($bac_reso->rfq_date)) }};</span>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-12" style="text-indent: 4rem;">
                        @php
                            $totalCompanies = count($companies);
                        @endphp
                        <span class="fw-bold">WHEREAS</span>, aside from the said advertisement, the Bids and Awards Committee, through its Secretariat, send RFQ to {{ convertNumberToWords($totalCompanies) }} ({{ $totalCompanies }}) suppliers to wit: <span class="fw-bold">
                            @for ($i = 0; $i < $totalCompanies; $i++)
                                {{ $companies[$i]->name }}@if ($i < $totalCompanies - 1), @endif
                            @endfor
                        </span>;
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-12" style="text-indent: 4rem;">
                        <span class="fw-bold">WHEREAS,</span> only 
                        @for ($i = 0; $i < $totalCompanies; $i++)
                            <span class="fw-bold">{{ $companies[$i]->name }}</span>{{ $i < $totalCompanies - 2 ? ", " : "" }}{{ $i == $totalCompanies - 2 ? " and " : "" }}
                        @endfor submitted an accomplished Request for Quotation;
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-12" style="text-indent: 4rem;">
                        <span class="fw-bold">WHEREAS,</span> Section V, Subsection D, Paragraph 8(b)(ii), Annex H of the IRR of R.A. No. 9184 otherwise known as “Government Procurement Reform Act,” provides that:
                    </div>
                    <div class="col-12" style="text-indent: 4rem;">
                        <div class="float-start">ii.</div> <div style="text-indent: 4rem;"><span class="fw-bold fst-italic">The BAC shall prepare and send the RFQs/RFPs to at least three (3) suppliers, contractors or consultants of known qualifications.</span> This, notwithstanding, those who responded through any of the required postings shall be allowed to participate. <span class="fw-bold fst-italic text-decoration-underline">Receipt of at least one (1) quotation is sufficient to proceed with the evaluation thereof.</span></div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-12" style="text-indent: 4rem;">
                        <span class="fw-bold">WHEREAS,</span> the opening of quotations was conducted at {{ $bac_reso->opening_quotation_location }} on <span class="fw-bold">{{ date('d F Y', strtotime($bac_reso->opening_quotation_date)) }}</span>;
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-12" style="text-indent: 4rem;">
                        <span class="fw-bold">WHEREAS,</span> <span class="fw-bold">{{ $bac_reso->bac_reso_items[0]->quotation->quotation->company->name }}</span> submitted the lowest calculated and responsive quotation for the goods to be procured;
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-12" style="text-indent: 4rem;">
                        <span class="fw-bold">NOW THEREFORE,</span> after meticulous perusal, validation and verification, We, the members of Bids and Awards Committee hereby certifies that the foregoing abstract of canvass is true and correct and that we have reviewed and evaluated the quotations by lot and hereby recommend to the University President to procure said requested goods from <span class="fw-bold">{{ $bac_reso->bac_reso_items[0]->quotation->quotation->company->name }}</span> amounting to <span class="fw-bold text-uppercase">{{ translateToWords($bac_reso->abstract_of_canvass->abc) }}</span> <span class="fw-bold">(Php{{ number_format($bac_reso->abstract_of_canvass->abc, 2) }});</span>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12" style="text-indent: 4rem;">
                        <span class="fw-bold">RESOLVED,</span> at the Bulacan State University, City of Malolos, Bulacan, this <span class="fw-bold">{{ ordinal(date('d', strtotime($bac_reso->created_at))) }} day of {{ date('F', strtotime($bac_reso->created_at)) }} {{ date('Y', strtotime($bac_reso->created_at)) }}</span>.
                    </div>
                </div>

                <div class="row mb-3 text-center">
                    <div class="col">
                        <div class="fw-bold text-uppercase">{{ $bac_reso->abstract_of_canvass->bac_chairman }}</div>
                        <div>Chair</div>
                    </div>
                    <div class="col">
                        <div class="fw-bold text-uppercase">{{ $bac_reso->abstract_of_canvass->vice_chairman }}</div>
                        <div>Vice Chair</div>
                    </div>
                    <div class="col">
                        <div class="fw-bold text-uppercase">{{ $bac_reso->abstract_of_canvass->member_1 }}</div>
                        <div>Member</div>
                    </div>
                    <div class="col">
                        <div class="fw-bold text-uppercase">{{ $bac_reso->abstract_of_canvass->member_2 }}</div>
                        <div>Member</div>
                    </div>
                    <div class="col">
                        <div class="fw-bold text-uppercase">{{ $bac_reso->abstract_of_canvass->member_3 }}</div>
                        <div>Member</div>
                    </div>
                </div>
                <div class="row mb-3 text-center">
                    <div class="col-4">
                        <div class="fw-bold text-uppercase">{{ $bac_reso->abstract_of_canvass->member_4 }}</div>
                        <div>Member</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold text-uppercase">{{ $bac_reso->abstract_of_canvass->end_user }}</div>
                        <div>End User</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold text-uppercase">{{ $bac_reso->abstract_of_canvass->technical_resource_person }}</div>
                        <div>Technical Resource Person</div>
                    </div>
                </div>
                <div class="row mb-2 text-center">
                    <div class="col-12 mb-5">
                        <div class="text-uppercase">Approved</div>
                    </div>
                    <div class="col-12">
                        <div class="fw-bold text-uppercase">{{ $bac_reso->abstract_of_canvass->president }}</div>
                        <div>President</div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            window.addEventListener('load', function () {
                window.print();
            })
        </script>
    </body>
</html>