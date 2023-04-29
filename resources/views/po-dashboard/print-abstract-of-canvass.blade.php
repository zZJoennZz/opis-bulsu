<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>
            Print Abstract of Canvass |  OPIS - BulSU e-PROCUREMENT
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
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="text-center">
                            <div>Republic of the Philippines</div>
                            <div class="fw-bold">BULACAN STATE UNIVERSITY</div>
                            <div class="fst-italic">City of Malolos, Bulacan</div>
                            <div class="fw-bold">ABSTRACT OF CANVASS</div>
                            <div class="fw-bold text-light">-</div>
                            <div class="fw-bold">
                                <span class="border-top border-dark">Date and Time of Opening</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <span class="fw-bold">
                            Purpose: {{ $aoc[0]->pr->purpose }}
                        </span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 fw-bold">ABC: {{ number_format($aoc[0]->abc, 2) }}</div>
                    <div class="col-6 text-end fw-bold">PR NUMBER: {{ $aoc[0]->pr->pr_number }}</div>
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
                                        <th style="width: 20%;" rowspan="3">NAME OF ARTICLES BEING REQUISITIONED</th>
                                        <th style="width: 5%;" rowspan="3">Unit</th>
                                        <th style="width: 5%;" rowspan="3">Qty.</th>
                                        <th style="width: 5%;" rowspan="3">Unit Price</th>
                                        <th style="width: 5%;" rowspan="3">Extended Amount</th>
                                        <th style="width: 60%;" colspan="9">NAME OF THE BIDDERS / DEALERS</th>
                                    </tr>
                                    <tr style="color: #1f497d;">
                                        @for ($i1 = 0; $i1 < 3; $i1++)
                                            @if ($currCompany <= count($companies) - 1)
                                                <th colspan="3">{{ $companies[$currCompany]->name }}</th>
                                                @php
                                                    array_push($companyId, $currCompany);
                                                    $currCompany += 1;
                                                @endphp
                                            @else
                                                <th colspan="3"></th>
                                            @endif
                                        @endfor
                                    </tr>
                                    <tr>
                                        <th>Unit Price</th>
                                        <th>Brand</th>
                                        <th>Extended Amount</th>
                                        <th>Unit Price</th>
                                        <th>Brand</th>
                                        <th>Extended Amount</th>
                                        <th>Unit Price</th>
                                        <th>Brand</th>
                                        <th>Extended Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $ctr = 1;
                                        $totalAmount = 0;
                                    @endphp
                                    @foreach ($aoc[0]->pr->pr_items as $item)
                                        <tr>
                                            @php
                                                $itemQty = 0;
                                                foreach ($item->ppmp->milestones as $milestone) {
                                                    $itemQty += $milestone->milestone_value;
                                                }
                                            @endphp
                                            <td class="text-center">{{ $ctr }}</td>
                                            <td>{{ $item->ppmp->item_detail->description }}</td>
                                            <td class="text-center">{{ $item->ppmp->item_detail->unit->uom }}</td>
                                            <td class="text-center">{{ $itemQty }}</td>
                                            <td class="text-end">{{ number_format($item->ppmp->item_detail->price_catalogue, 2) }}</td>
                                            <td class="text-end">{{ number_format($item->ppmp->item_detail->price_catalogue * $itemQty, 2) }}</td>


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
                                                            <td class="text-end">{{ number_format($cqitem->offered_unit_price, 2) }}</td>
                                                            <td class="text-end">{{ $cqitem->brand_and_model_offered }}</td>
                                                            <td class="text-end">{{ number_format($cqitem->offered_unit_price * $itemQty, 2) }}</td>
                                                            @php
                                                                $itemsFound += 1;
                                                                $compExtendedTotal[$totalCtr] += $cqitem->offered_unit_price * $itemQty;
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                @endforeach

                                                @if ($itemsFound === 0)
                                                    <td class="text-center" style="font-size: 11px;">N/A</td>
                                                    <td class="text-center" style="font-size: 11px;">N/A</td>
                                                    <td class="text-center" style="font-size: 11px;">N/A</td>
                                                @endif
                                                @php
                                                    $totalCtr += 1;
                                                @endphp
                                                {{-- @if ($currCompany <= count($companies) - 1)
                                                    @php
                                                        $itemsFound = 0;
                                                    @endphp
                                                    @foreach ($companies[$cid]->quotations as $cq)
                                                        @foreach ($cq->items as $cqitem)
                                                            @if ($item->id === $cqitem->purchase_request_items_id)
                                                                <td class="text-end">{{ number_format($cqitem->offered_unit_price, 2) }}</td>
                                                                <td class="text-end">{{ $cqitem->brand_and_model_offered }}</td>
                                                                <td class="text-end">{{ number_format($cqitem->offered_unit_price * $itemQty, 2) }}</td>
                                                                @php
                                                                    $itemsFound += 1;
                                                                @endphp
                                                            @endif
                                                        @endforeach
                                                    @endforeach

                                                    @if ($itemsFound === 0)
                                                        <td class="text-center" style="font-size: 11px;">N/A 1</td>
                                                        <td class="text-center" style="font-size: 11px;">N/A 1</td>
                                                        <td class="text-center" style="font-size: 11px;">N/A 1</td>
                                                    @endif
                                                    @php
                                                        $currCompany1 += 1;
                                                    @endphp
                                                @else
                                                    <td class="text-center" style="font-size: 11px;">N/A 2</td>
                                                    <td class="text-center" style="font-size: 11px;">N/A 2</td>
                                                    <td class="text-center" style="font-size: 11px;">N/A 2</td>
                                                @endif --}}
                                            @endforeach
                                            @for ($i3 = 0; $i3 < 3 -  count($companyId); $i3++)
                                                <td class="text-center" style="font-size: 11px;">N/A</td>
                                                <td class="text-center" style="font-size: 11px;">N/A</td>
                                                <td class="text-center" style="font-size: 11px;">N/A</td>
                                            @endfor
                                            @php
                                                $ctr += 1;
                                            @endphp
                                        </tr>
                                    @endforeach

                                    {{-- @for ($i4 = 0; $i4 < 10 - count($aoc[0]->pr->pr_items); $i4++)
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
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endfor --}}
                                </tbody>
                                <tfoot>
                                    <tr style="border: 0px !important;" class="fw-bold">
                                        <td style="border: 0px !important;"></td>
                                        <td style="border: 0px !important;"></td>
                                        <td style="border: 0px !important;"></td>
                                        <td style="border: 0px !important;"></td>
                                        <td style="border: 0px !important;"">TOTAL</td>
                                        <td style="border: 0px !important;" class="text-end">{{ number_format($totalAmount, 2) }}</td>
                                        <td style="border: 0px !important;"></td>
                                        <td style="border: 0px !important;"></td>
                                        <td style="border: 0px !important;" class="text-end">{{ number_format($compExtendedTotal[0], 2) }}</td>
                                        <td style="border: 0px !important;"></td>
                                        <td style="border: 0px !important;"></td>
                                        <td style="border: 0px !important;" class="text-end">{{ number_format($compExtendedTotal[1], 2) }}</td>
                                        <td style="border: 0px !important;"></td>
                                        <td style="border: 0px !important;"></td>
                                        <td style="border: 0px !important;" class="text-end">{{ number_format($compExtendedTotal[2], 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
            @endfor
            <div class="row mb-2">
                <div class="col-12 text-center">
                    We the undersigned hereby certify that the foregoing abstract of canvass is true and correct and that we have reviewed and evaluated the quotations and hereby recommended the following  proposed award:
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-8"><strong>ITEMS:</strong> _____________________________________________________________________________</div>
                <div class="col-4"><strong>AWARD IS GIVEN TO:</strong></div>

                <div class="col-12">
                    THE PRICE ARE THE LOWEST OBTAINABLE OFFER DURING THE TIME OF CANVASS AND THESE ARE DETERMINED TO BE REASONABLE AND ADVANTAGEOUS TO THE UNIVERSITY.
                </div>
            </div>

            <div class="row text-center container-fluid mb-5">
                <div class="col-2 fw-bold">
                    <div class="border-bottom border-dark w-100">{{ $aoc[0]->bac_chairman }}</div>
                    <div class="w-100">BAC Chairman</div>
                </div>
                
                <div class="col-2 fw-bold">
                    <div class="border-bottom border-dark w-100">{{ $aoc[0]->vice_chairman }}</div>
                    <div class="w-100">Vice Chairman</div>
                </div>
                
                <div class="col-2 fw-bold">
                    <div class="border-bottom border-dark w-100">{{ $aoc[0]->member_1 }}</div>
                    <div class="w-100">Member</div>
                </div>

                <div class="col-2 fw-bold">
                    <div class="border-bottom border-dark w-100">{{ $aoc[0]->member_2 }}</div>
                    <div class="w-100">Member</div>
                </div>

                <div class="col-2 fw-bold">
                    <div class="border-bottom border-dark w-100">{{ $aoc[0]->member_3 }}</div>
                    <div class="w-100">Member</div>
                </div>

                <div class="col-2 fw-bold">
                    <div class="border-bottom border-dark w-100">{{ $aoc[0]->member_4 }}</div>
                    <div class="w-100">Member</div>
                </div>
            </div>

            <div class="row text-center m-auto w-75 mb-5">
                <div class="col-4 fw-bold">
                    <div class="border-bottom border-dark w-100">{{ $aoc[0]->end_user }}</div>
                    <div class="w-100">Requesting Officer / End-User</div>
                </div>
                
                <div class="col-4 fw-bold">
                    <div class="border-bottom border-dark w-100">{{ $aoc[0]->technical_resource_person }}</div>
                    <div class="w-100">Technical Resource Person</div>
                </div>
                
                <div class="col-4 fw-bold">
                    <div class="border-bottom border-dark w-100">{{ $aoc[0]->procurement_office_rep }}</div>
                    <div class="w-100">Procurement Office's Representative</div>
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