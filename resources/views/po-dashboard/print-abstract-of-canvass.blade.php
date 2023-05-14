<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>
            Abstract of Canvass |  OPIS - BulSU e-PROCUREMENT
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
                    <div class="col-7 m-auto">
                        {{-- <img src="{{ asset('img/bsu-small-logo.png') }}" alt="BSU Logo" style="position: absolute; top: 0; left: 20vw; width: 75px;" /> --}}
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
                                        <th style="width: 2%;" rowspan="3">Item No.</th>
                                        <th style="width: 30%;" rowspan="3">NAME OF ARTICLES BEING REQUISITIONED</th>
                                        <th style="width: 2%;" rowspan="3">Unit</th>
                                        <th style="width: 2%;" rowspan="3">Qty.</th>
                                        <th style="width: 5%;" rowspan="3">Unit Price</th>
                                        <th style="width: 5%;" rowspan="3">Extended Amount</th>
                                        <th style="width: 54%;" colspan="9">NAME OF BIDDERS</th>
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
                                                            @php
                                                                $selCompanyClass = "";
                                                            @endphp
                                                            @if (count($bac_reso->bac_reso_items->where('quotation_items_id', $cqitem->id)) === 1)
                                                                @php
                                                                    $selCompanyClass = "text-end fw-bold";
                                                                @endphp
                                                            @else
                                                                @php
                                                                    $selCompanyClass = "text-end";
                                                                @endphp
                                                            @endif
                                                            <td class="{{ $selCompanyClass }}">{{ number_format($cqitem->offered_unit_price, 2) }}</td>
                                                            <td class="{{ $selCompanyClass }}">{{ $cqitem->brand_and_model_offered }}</td>
                                                            <td class="{{ $selCompanyClass }}">{{ number_format($cqitem->offered_unit_price * $itemQty, 2) }}</td>
                                                            @php
                                                                $compExtendedTotal[$totalCtr] += $cqitem->offered_unit_price * $itemQty;
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
                                                @php
                                                    $totalCtr += 1;
                                                @endphp
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
                                    <tr class="fw-bold" style="border: none !important;">
                                        <td style="border: none !important;" class="text-end" colspan="5">TOTAL</td>
                                        <td style="border: none !important;" class="text-end">{{ number_format($totalAmount, 2) }}</td>
                                        <td style="border: none !important;" class="text-end" colspan="3">{{ number_format($compExtendedTotal[0], 2) }}</td>
                                        <td style="border: none !important;" class="text-end" colspan="3">{{ number_format($compExtendedTotal[1], 2) }}</td>
                                        <td style="border: none !important;" class="text-end" colspan="3">{{ number_format($compExtendedTotal[2], 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
            @endfor
                <div class="row mb-2">
                    <div class="col-12" style="text-indent: 4rem;">
                        We the undersigned hereby certify that the foregoing abstract of canvass is true and correct and that we have reviewed and evaluated the quotations and hereby recommended the following  proposed award:
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-8"><strong>ITEMS:</strong> 
                        <div class="d-inline border-bottom border-dark">
                            @for ($i = 0; $i < count($bac_reso->abstract_of_canvass->pr->pr_items); $i++)
                                {{ $bac_reso->abstract_of_canvass->pr->pr_items[$i]->ppmp->item_detail->description }}@if ($i !== count($bac_reso->abstract_of_canvass->pr->pr_items) - 1), @endif
                            @endfor
                        </div>
                        
                    </div>
                    <div class="col-4">
                        <strong>AWARD IS GIVEN TO:</strong>
                        <div class="d-inline w-100">
                            @if ($bac_reso->abstract_of_canvass->type === "BY_ITEM")
                                @foreach ($bac_reso->bac_reso_items as $item)
                                    <div class="border-bottom border-dark">{{ $item->quotation->pr_item->ppmp->item_detail->description }} - <strong>{{ $item->quotation->quotation->company->name }}</strong></div>
                                @endforeach
                            @else
                                <div class="border-bottom border-dark d-inline">
                                    <strong>{{ $bac_reso->bac_reso_items[0]->quotation->quotation->company->name }}</strong>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        THE PRICE ARE THE LOWEST OBTAINABLE OFFER DURING THE TIME OF CANVASS AND THESE ARE DETERMINED TO BE REASONABLE AND ADVANTAGEOUS TO THE UNIVERSITY.
                    </div>
                </div>

                <div class="row mb-3 text-center">
                    <div class="col-3">
                        <div class="fw-bold text-uppercase">{{ $bac_reso->abstract_of_canvass->bac_chairman }}</div>
                        <div class="w-50 m-auto border-top border-dark">Chair</div>
                    </div>
                    <div class="col-3">
                        <div class="fw-bold text-uppercase">{{ $bac_reso->abstract_of_canvass->member_1 }}</div>
                        <div class="w-50 m-auto border-top border-dark">Member</div>
                    </div>
                    <div class="col-3">
                        <div class="fw-bold text-uppercase">{{ $bac_reso->abstract_of_canvass->member_2 }}</div>
                        <div class="w-50 m-auto border-top border-dark">Member</div>
                    </div>
                    <div class="col-3">
                        <div class="fw-bold text-uppercase">{{ $bac_reso->abstract_of_canvass->member_3 }}</div>
                        <div class="w-50 m-auto border-top border-dark">Member</div>
                    </div>
                </div>
                <div class="row mb-3 text-center">
                    <div class="col-4">
                        <div class="fw-bold text-uppercase">{{ $bac_reso->abstract_of_canvass->member_4 }}</div>
                        <div class="w-50 m-auto border-top border-dark">Member</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold text-uppercase">{{ $bac_reso->abstract_of_canvass->end_user }}</div>
                        <div class="w-50 m-auto border-top border-dark">End User</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold text-uppercase">{{ $bac_reso->abstract_of_canvass->technical_resource_person }}</div>
                        <div class="w-50 m-auto border-top border-dark">Technical Resource Person</div>
                    </div>
                </div>
                <div class="row mb-2 text-center">
                    <div class="col-12 mb-5">
                        <div class="text-uppercase">Approved</div>
                    </div>
                    <div class="col-12">
                        <div class="fw-bold text-uppercase">{{ $bac_reso->abstract_of_canvass->president }}</div>
                        <div class="w-25 m-auto border-top border-dark">President</div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <script>
            window.addEventListener('load', function () {
                window.print();
            })
        </script> --}}
    </body>
</html>