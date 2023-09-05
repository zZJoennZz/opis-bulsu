<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>
            {{ $endUser->first_name . ' ' . $endUser->middle_name . ' ' . $endUser->last_name }} RPCSP for Below P50K |  OPIS - BulSU e-PROCUREMENT
        </title>
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <link rel="manifest" href="{{ asset('site.webmanifest') }}" crossorigin="use-credentials" />
        <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
        <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('js/jquery-3.6.3.min.js') }}"></script>
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
        <div class="to-print">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="text-center">
                            <div style="font-size: 14px;" class="fw-bold">REPORT ON THE PHYSICAL COUNT OF SEMI-EXPENDABLE PROPERTY (RPCSP)</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-4"></div>
                    <div class="col-4">
                        <div class="text-center border-bottom border-dark">-</div>
                        <div class="text-center text-xs fst-italic">(Type of Semi-expendable Property)</div>
                        
                    </div>
                    <div class="col-4"></div>
                </div>

                <div class="row mb-4">
                    <div class="col-4 text-end">As at</div>
                    <div class="col-4">
                        <div class="text-center border-bottom border-dark">-</div>
                    </div>
                    <div class="col-4"></div>
                </div>

                <div class="row mb-4">
                    <div class="col-12 row">
                        <div class="col-1">Fund Cluster:</div>
                        <div class="col-5 border-bottom border-dark"></div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-12">
                        For which, <span class="text-decoration-underline">{{ $endUser->first_name . ' ' . $endUser->middle_name . ' ' . $endUser->last_name }}</span>, <span class="text-decoration-underline">{{ $endUser->position->name }}</span>, <span class="text-decoration-underline">{{ $endUser->branch->branch_name }}</span> is accountable, having assumed such accountability on ______________.
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <table class="w-100">
                            <thead class="text-center">
                                <tr>
                                    <th class="border border-dark" rowspan="2" style="width: 10%;">Article</th>
                                    <th class="border border-dark" rowspan="2" style="width: 30%;">Description</th>
                                    <th class="border border-dark" rowspan="2" style="width: 10%;">Semi-expendable Property No.</th>
                                    <th class="border border-dark" rowspan="2" style="width: 5%;">Unit of Measure</th>
                                    <th class="border border-dark" rowspan="2" style="width: 10%;">Unit Value</th>
                                    <th class="border border-dark" style="width: 5%;">Balance Per Card</th>
                                    <th class="border border-dark" style="width: 5%;">On Hand Per Count</th>
                                    <th class="border border-dark" colspan="2" style="width: 10%;">Shortage/Overage</th>
                                    <th class="border border-dark" rowspan="2" style="width: 20%;">Remarks</th>
                                </tr>
                                <tr>
                                    <td class="border border-dark">(Quantity)</td>
                                    <td class="border border-dark">(Quantity)</td>
                                    <td class="border border-dark">(Quantity)</td>
                                    <td class="border border-dark">(Quantity)</td>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($eqCodesHighValue) > 0)
                                    <tr>
                                        <td colspan="2" class="border border-dark fw-bold">
                                            Below P50,000 - HIGH VALUED ITEMS
                                        </td>
                                        <td class="border border-dark"></td>
                                        <td class="border border-dark"></td>
                                        <td class="border border-dark"></td>
                                        <td class="border border-dark"></td>
                                        <td class="border border-dark"></td>
                                        <td class="border border-dark"></td>
                                        <td class="border border-dark"></td>
                                        <td class="border border-dark"></td>
                                    </tr>
                                    @php
                                        $lastEqId = 0;
                                    @endphp
                                    @foreach ($eqCodesHighValue as $highValue)
                                        @foreach ($highValue->items as $item)
                                            @if ($item->properties[0]->serial_number !== "n/a")
                                                @foreach ($item->properties as $property)
                                                <tr>
                                                    <td class="border border-dark">
                                                        @if ($lastEqId !== $highValue->id)
                                                            @if ($highValue->article === "SEMI_EXPENDABLE")
                                                                <div>Semi-expendable</div>
                                                            @endif
                                                            {{ $highValue->description }}
                                                            @php
                                                                $lastEqId = $highValue->id;
                                                            @endphp
                                                        @endif
                                                    </td>
                                                    <td class="border border-dark">
                                                        <div>{{ $item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}, {{ $item->bac_reso_item->quotation->brand_and_model_offered }} SN: {{ $property->serial_number }}</div>
                                                        <div class="fw-bold">
                                                            Date Acquired: {{ date('m-d-Y', strtotime($item->transaction->date_acquired)) }}
                                                        </div>
                                                    </td>
                                                    <td class="border border-dark">
                                                        {{ $item->property_number }}
                                                    </td>
                                                    <td class="border border-dark">
                                                        {{ $item->bac_reso_item->quotation->pr_item->ppmp->item_detail->unit->uom }}
                                                    </td>
                                                    <td class="border border-dark">
                                                        ₱ {{ number_format($item->bac_reso_item->quotation->offered_unit_price, 2) }}
                                                    </td>
                                                    <td class="border border-dark">1</td>
                                                    <td class="border border-dark"></td>
                                                    <td class="border border-dark"></td>
                                                    <td class="border border-dark"></td>
                                                    <td class="border border-dark"></td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td class="border border-dark">
                                                        @if ($lastEqId !== $highValue->id)
                                                            @if ($highValue->article === "SEMI_EXPENDABLE")
                                                                <div>Semi-expendable</div>
                                                            @endif
                                                            {{ $highValue->description }}
                                                            @php
                                                                $lastEqId = $highValue->id;
                                                            @endphp
                                                        @endif
                                                    </td>
                                                    <td class="border border-dark">
                                                        <div>{{ $item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}, {{ $item->bac_reso_item->quotation->brand_and_model_offered }}</div>
                                                        <div class="fw-bold">
                                                            Date Acquired: {{ date('m-d-Y', strtotime($item->transaction->date_acquired)) }}
                                                        </div>
                                                    </td>
                                                    <td class="border border-dark">
                                                        {{ $item->property_number }}
                                                    </td>
                                                    <td class="border border-dark">
                                                        {{ $item->bac_reso_item->quotation->pr_item->ppmp->item_detail->unit->uom }}
                                                    </td>
                                                    <td class="border border-dark">
                                                        ₱ {{ number_format($item->bac_reso_item->quotation->offered_unit_price, 2) }}
                                                    </td>
                                                    <td class="border border-dark">
                                                        {{ $item->quantity }}
                                                    </td>
                                                    <td class="border border-dark"></td>
                                                    <td class="border border-dark"></td>
                                                    <td class="border border-dark"></td>
                                                    <td class="border border-dark"></td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endforeach
                                @endif
                                @if (count($eqCodesLowValue) > 0)
                                    <tr>
                                        <td colspan="2" class="border border-dark fw-bold">
                                            Below P50,000 - LOW VALUED ITEMS
                                        </td>
                                        <td class="border border-dark"></td>
                                        <td class="border border-dark"></td>
                                        <td class="border border-dark"></td>
                                        <td class="border border-dark"></td>
                                        <td class="border border-dark"></td>
                                        <td class="border border-dark"></td>
                                        <td class="border border-dark"></td>
                                        <td class="border border-dark"></td>
                                    </tr>
                                    @php
                                        $lastEqId = 0;
                                    @endphp
                                    @foreach ($eqCodesLowValue as $lowValue)
                                        @foreach ($lowValue->items as $item)
                                            @if ($item->properties[0]->serial_number !== "n/a")
                                                @foreach ($item->properties as $property)
                                                <tr>
                                                    <td class="border border-dark">
                                                        @if ($lowValue->article === "SEMI_EXPENDABLE")
                                                            <div>Semi-expendable</div>
                                                        @endif
                                                        @if ($lastEqId !== $lowValue->id)
                                                            {{ $lowValue->description }}
                                                            @php
                                                                $lastEqId = $lowValue->id;
                                                            @endphp
                                                        @endif
                                                    </td>
                                                    <td class="border border-dark">
                                                        <div>{{ $item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}, {{ $item->bac_reso_item->quotation->brand_and_model_offered }} SN: {{ $property->serial_number }}</div>
                                                        <div class="fw-bold">
                                                            Date Acquired: {{ date('m-d-Y', strtotime($item->transaction->date_acquired)) }}
                                                        </div>
                                                    </td>
                                                    <td class="border border-dark">
                                                        {{ $item->property_number }}
                                                    </td>
                                                    <td class="border border-dark">
                                                        {{ $item->bac_reso_item->quotation->pr_item->ppmp->item_detail->unit->uom }}
                                                    </td>
                                                    <td class="border border-dark">
                                                        ₱ {{ number_format($item->bac_reso_item->quotation->offered_unit_price, 2) }}
                                                    </td>
                                                    <td class="border border-dark">1</td>
                                                    <td class="border border-dark"></td>
                                                    <td class="border border-dark"></td>
                                                    <td class="border border-dark"></td>
                                                    <td class="border border-dark"></td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td class="border border-dark">
                                                        @if ($lowValue->article === "SEMI_EXPENDABLE")
                                                            <div>Semi-expendable</div>
                                                        @endif
                                                        @if ($lastEqId !== $lowValue->id)
                                                            {{ $lowValue->description }}
                                                            @php
                                                                $lastEqId = $lowValue->id;
                                                            @endphp
                                                        @endif
                                                    </td>
                                                    <td class="border border-dark">
                                                        <div>{{ $item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}, {{ $item->bac_reso_item->quotation->brand_and_model_offered }}</div>
                                                        <div class="fw-bold">
                                                            Date Acquired: {{ date('m-d-Y', strtotime($item->transaction->date_acquired)) }}
                                                        </div>
                                                    </td>
                                                    <td class="border border-dark">
                                                        {{ $item->property_number }}
                                                    </td>
                                                    <td class="border border-dark">
                                                        {{ $item->bac_reso_item->quotation->pr_item->ppmp->item_detail->unit->uom }}
                                                    </td>
                                                    <td class="border border-dark">
                                                        ₱ {{ number_format($item->bac_reso_item->quotation->offered_unit_price, 2) }}
                                                    </td>
                                                    <td class="border border-dark">
                                                        {{ $item->quantity }}
                                                    </td>
                                                    <td class="border border-dark"></td>
                                                    <td class="border border-dark"></td>
                                                    <td class="border border-dark"></td>
                                                    <td class="border border-dark"></td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endforeach
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="10" class="border border-dark">
                                        <div class="row">
                                            <div class="col-4">
                                                <div>Certified Correct by:</div>
                                                <div class="text-center">
                                                    <div class="w-50 m-auto border-bottom border-dark">JOANHA CHRISTINE BORJA</div>
                                                    <div class="mb-2">Signature over Printed Name of Inventory Committee Chair</div>
                                                    <div class="mb-3 w-50 m-auto border-bottom border-dark">LEAH C. CRUZ</div>
                                                    <div class="mb-3 w-50 m-auto border-bottom border-dark">SHARON ALMARIO</div>
                                                    <div class="w-50 m-auto border-bottom border-dark">JESSICA GONZALES</div>
                                                    <div>Signature over Printed Name of Inventory Committee Members</div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div>Approved by:</div>
                                                <div class="w-50 pt-5 m-auto border-bottom border-dark text-center">DR. CECILIA N. GASCON</div>
                                                <div class="text-center">Signature over Printed Name of Head of</div>
                                                <div class="text-center">Agency/Entity or Authorized Representative</div>
                                            </div>
                                            <div class="col-4">
                                                <div>Witnessed by:</div>
                                                <div class="w-50 pt-5 m-auto border-bottom border-dark text-center text-light">-</div>
                                                <div class="text-center">Signature over Printed Name of </div>
                                                <div class="text-center">COA Representative</div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>