<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>
        Print Physical Count of Property, Plant and Equipment
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
            font-family: 'Times New Roman', Times, serif;
            font-size: 14px;
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
    @php
        $content = json_decode($report->content, false, 512, JSON_UNESCAPED_SLASHES);
    @endphp
    <div class="to-print">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12 text-end fst-italic" style="font-size: 16px;">
                    Appendix 73
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="text-center text-uppercase fw-bold" style="font-size: 16px;">
                        Report on the physical count of property, plant and equipment
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="text-center text-uppercase border-bottom border-dark w-25 m-auto">{{ pluralize($content->eqCode->description) }}</div>
                    <div class="text-center fst-italic">(Type of Property, Plant and Equipment)</div>
                    <div class="text-center"><span class="fw-bold">As at</span> <span class="text-decoration-underline">{{ Carbon\Carbon::parse($report->created_at)->format('Y-m-d H:i A') }}</span></div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <div class="fw-bold">
                        Fund Cluster: <div class="d-inline border-dark text-uppercase">______________________________</div>
                    </div>
                </div>
                <div class="col-12" style="font-size: 13px;">
                    For which <span class="text-decoration-underline">{{ $content->endUser->first_name . ' ' . $content->endUser->middle_name . ' ' . $content->endUser->last_name }}</span>, <span class="text-decoration-underline">{{ $content->endUser->position->name }}</span>, <span class="text-decoration-underline">{{ $content->endUser->branch->branch_name }}</span> is accountable, having assumed such acccountability on ________________.
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <table class="w-100">
                        <caption></caption>
                        <thead class="align-middle text-center">
                            <tr>
                                <th style="font-size: 11px !important; border: 1px solid #000; width: 10%;" rowspan="2">ARTICLE</th>
                                <th style="font-size: 11px !important; border: 1px solid #000; width: 20%;" rowspan="2">DESCRIPTION</th>
                                <th style="font-size: 11px !important; border: 1px solid #000; width: 10%;" rowspan="2">PROPERTY<br />NUMBER</th>
                                <th style="font-size: 11px !important; border: 1px solid #000; width: 5%;" rowspan="2">UNIT OF<br />MEASUREMENT</th>
                                <th style="font-size: 11px !important; border: 1px solid #000; width: 5%;" rowspan="2">UNIT VALUE</th>
                                <th style="font-size: 11px !important; border: 1px solid #000; width: 10%;" rowspan="2">QUANTITY<br />per<br />PROPERTY CARD</th>
                                <th style="font-size: 11px !important; border: 1px solid #000; width: 10%;" rowspan="2">QUANTITY<br />per<br />PHYSICAL COUNT</th>
                                <th style="font-size: 11px !important; border: 1px solid #000; width: 10%;" colspan="2">SHORTAGE/OVERAGE</th>
                                <th style="font-size: 11px !important; border: 1px solid #000; width: 10%;" rowspan="2">Remarks</th>
                            </tr>
                            <tr>
                                <td style="font-size: 11px !important; border: 1px solid #000;">Quantity</td>
                                <td style="font-size: 11px !important; border: 1px solid #000;">Value</td>
                            </tr>
                        </thead>
                        <tbody class="text-center"
                            style="border-left: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">
                            @php
                                $eqCode = "";
                            @endphp
                            @foreach ($content->items as $item)
                            <tr>
                                <td style="font-size: 11px !important; border-right: 1px solid #000;">
                                    @if ($eqCode !== $item->equipment_code->id)
                                        {{ $item->equipment_code->description }}
                                    @endif
                                </td>
                                <td style="font-size: 11px !important; border-right: 1px solid #000;">
                                    {{ $item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}, {{ $item->bac_reso_item->quotation->brand_and_model_offered }}
                                </td>
                                <td style="font-size: 11px !important; border-right: 1px solid #000;">{{ $item->property_number }}</td>
                                <td style="font-size: 11px !important; border-right: 1px solid #000;">{{ $item->bac_reso_item->quotation->pr_item->ppmp->item_detail->unit->uom }}</td>
                                <td style="font-size: 11px !important; border-right: 1px solid #000;">₱ {{ number_format($item->bac_reso_item->quotation->offered_unit_price, 2) }}</td>
                                <td style="font-size: 11px !important; border-right: 1px solid #000;">{{ count($item->properties) }}</td>
                                <td style="font-size: 11px !important; border-right: 1px solid #000;"></td>
                                <td style="font-size: 11px !important; border-right: 1px solid #000;"></td>
                                <td style="font-size: 11px !important; border-right: 1px solid #000;"></td>
                                <td style="font-size: 11px !important;"></td>
                            </tr>

                            @php
                                $eqCode = $item->equipment_code->id;
                            @endphp
                            @endforeach
                        </tbody>
                        <tfoot style="border-left: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">
                            <tr>
                                <td colspan="4">
                                    <div class="mt-2" style="font-size: 11px !important;">
                                        Certified Correct by:
                                    </div>
                                    <div class="w-50 border-bottom border-dark m-auto text-center" style="font-size: 12px !important;">{{ $content->inventoryCommitteeChair }}</div>
                                    <div style="font-size: 11px !important;" class="text-center mb-3">Signature over Printed Name of Inventory Committee Chair</div>
                                    
                                    @foreach (json_decode($content->inventoryCommitteeMembers) as $item)
                                        <div class="w-50 border-bottom border-dark m-auto text-center mb-3" style="font-size: 12px !important;">{{ $item }}</div>
                                    @endforeach
                                    <div style="font-size: 11px !important; margin-top: -0.7rem" class="text-center">Signature over Printed Name of Inventory Committee Members</div>
                                </td>
                                <td colspan="4" style="vertical-align: text-top;">
                                    <div class="mt-2 mb-5" style="font-size: 11px !important;">
                                        Approved by:
                                    </div>
                                    <div class="w-50 border-bottom border-dark m-auto text-center" style="font-size: 12px !important;">{{ $content->headOfAgency }}</div>
                                    <div style="font-size: 11px !important;" class="text-center mb-3">Signature over Printed Name of Head of<br />Agency/Entity or Authorized Representative</div>
                                </td>
                                <td colspan="2" style="vertical-align: text-top;">
                                    <div class="mt-2 mb-5" style="font-size: 11px !important;">
                                        Verified by:
                                    </div>
                                    <div class="border-bottom border-dark m-auto text-center" style="font-size: 12px !important; width: 90%;"><span class="text-light">.</span></div>
                                    <div style="font-size: 11px !important;" class="text-center mb-3">Signature over Printed Name of<br />COA Representative</div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script defer>
        document.addEventListener('DOMContentLoaded', function() {
            window.print();
        });
    </script>
</body>

</html>