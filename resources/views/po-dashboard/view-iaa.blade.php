<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>
            Inspection and Acceptance {{ $iaa[0]->iar_no }}
        </title>
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <link rel="manifest" href="{{ asset('site.webmanifest') }}" crossorigin="use-credentials" />
        <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <style media="print">
            * {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 12px;
            }

            @media print {
                @page {
                    margin: 5px 0px 0px 5px;
                    page-break-after: always;
                }
            }
        </style>
    </head>
    <body>
        <div style="width: 98%;" class="m-auto small">
            <table class="w-100">
                <thead class="border-bottom border-dark">
                    <tr class="border-bottom border-dark">
                        <th colspan="4">
                            <div class="row mb-5">
                                <div class="col-12">
                                    <div class="fw-bold fs-4 text-center">
                                        INSPECTION AND ACCEPTANCE REPORT
                                    </div>
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr class="border-bottom border-end border-start border-dark">
                        <th class="border-end border-dark" style="font-weight: normal !important;">Entity Name:</th>
                        <th class="border-end border-dark">BULACAN STATE UNIVERSITY</th>
                        <th class="border-end border-dark" style="font-weight: normal !important;">Fund Cluster:</th>
                        <th>{{ $iaa[0]->purchase_order->bac_reso->abstract_of_canvass->pr->pr_items[0]->ppmp->source_of_fund->source_of_fund }}</th>
                    </tr>
                    <tr class="border-bottom border-end border-start border-dark">
                        <th class="border-end border-dark" style="font-weight: normal !important;">Supplier:</th>
                        <th class="border-end border-dark">{{ $iaa[0]->purchase_order->company->name }}</th>
                        <th class="border-end border-dark" style="font-weight: normal !important;">IAR No.:</th>
                        <th>{{ $iaa[0]->iar_no }}</th>
                    </tr>
                    <tr class="border-bottom border-end border-start border-dark">
                        <th class="border-end border-dark" style="font-weight: normal !important;" colspan="2">PO No. / Date: <strong>{{ $iaa[0]->purchase_order->po_number }}</strong> / <strong>{{ $iaa[0]->purchase_order->created_at }}</strong></th>
                        <th class="border-end border-dark" style="font-weight: normal !important;">Date:</th>
                        <th>{{ $iaa[0]->iar_date }}</th>
                    </tr>
                    <tr class="border-bottom border-end border-start border-dark">
                        <th class="border-end border-dark" colspan="2">
                            <div style="font-weight: normal !important;" class="d-inline">Requisitioning Office / Dept: {{ $iaa[0]->purchase_order->bac_reso->abstract_of_canvass->pr->office }}</div>
                        </th>
                        <th class="border-end border-dark" style="font-weight: normal !important;">Invoice No.:</th>
                        <th></th>
                    </tr>
                    <tr class="border-bottom border-end border-start border-dark">
                        <th class="border-end border-dark" colspan="2">
                            <div style="font-weight: normal !important;" class="d-inline">Responsibility Center Code: {{ $iaa[0]->purchase_order->bac_reso->abstract_of_canvass->pr->responsibility_center_code }}</div>
                        </th>
                        <th class="border-end border-dark" style="font-weight: normal !important;">Date:</th>
                        <th></th>
                    </tr>
                    <tr class="border-dark border-end border-start text-center">
                        <th style="width: 10%;" class="border-end border-dark">Stock/<br />Property No.</th>
                        <th style="width: 65%;" class="border-end border-dark">Description</th>
                        <th style="width: 10%;" class="border-end border-dark">Unit</th>
                        <th style="width: 15%;">Quantity</th>
                    </tr>
                </thead>
                <tbody class="border border-dark">
                    <tr class="border-bottom border-dark">
                        <td class="border-end border-dark text-center"></td>
                        <td class="border-end border-dark text-danger">Purpose: {{ $iaa[0]->purchase_order->bac_reso->abstract_of_canvass->pr->purpose }}</td>
                        <td class="border-end border-dark text-center"></td>
                        <td class="text-center"></td>
                    </tr>
                    <tr class="border-bottom border-dark">
                        <td class="border-end border-dark text-center text-light">-</td>
                        <td class="border-end border-dark text-danger"></td>
                        <td class="border-end border-dark text-center"></td>
                        <td class="text-center"></td>
                    </tr>
                    @php
                        $ctr = 1;
                    @endphp
                    @foreach ($iaa[0]->purchase_order->bac_reso->bac_reso_items as $item)
                        @if ($item->quotation->quotation->companies_id === $iaa[0]->purchase_order->company->id)
                            <tr class="border-bottom border-dark">
                                @php
                                    $totalQty = 0;
                                    foreach($item->quotation->pr_item->ppmp->milestones as $m) {
                                        $totalQty += $m->milestone_value;
                                    }
                                @endphp
                                <td class="border-end border-dark text-center">{{ $ctr }}</td>
                                <td class="border-end border-dark">{{ $item->quotation->pr_item->ppmp->item_detail->description }}</td>
                                <td class="border-end border-dark text-center">{{ $item->quotation->pr_item->ppmp->item_detail->unit->uom }}</td>
                                <td class="text-center">{{ $totalQty }}</td>
                            </tr>
                            <tr class="border-bottom border-dark">
                                <td class="border-end border-dark text-center"></td>
                                <td class="border-end border-dark">{{ $item->quotation->pr_item->ppmp->item_detail->article }}</td>
                                <td class="border-end border-dark text-center"></td>
                                <td class="text-center"></td>
                            </tr>
                            <tr class="border-bottom border-dark">
                                <td class="border-end border-dark text-center"></td>
                                <td class="border-end border-dark">{{ $item->quotation->pr_item->ppmp->item_detail->extra_article }}</td>
                                <td class="border-end border-dark text-center"></td>
                                <td class="text-center"></td>
                            </tr>
                            <tr class="border-bottom border-dark">
                                <td class="border-end border-dark text-center text-light">-</td>
                                <td class="border-end border-dark"></td>
                                <td class="border-end border-dark text-center"></td>
                                <td class="text-center"></td>
                            </tr>
                        @endif
                        @php
                            $ctr += 1;
                        @endphp
                    @endforeach
                </tbody>
                <tfoot class="border-start border-end border-dark" style="padding: 0px !important;">
                    <tr>
                        <td colspan="4" class="p-0 m-0" style="padding: 0px !important;">
                            <div class="row p-0 m-0 border-bottom border-dark" style="padding: 0px !important;">
                                <div class="col-6 border-end border-dark p-0">
                                    <div class="border-bottom border-dark fw-bold fst-italic fs-5 text-center">
                                        INSPECTION
                                    </div>
                                    <div class="p-5">
                                        <div class="mb-3" style="font-size: 14px;">
                                            Date Inspected:
                                        </div>
                                        <div class="w-75 m-auto" style="font-size: 14px;">
                                            <div class="border border-dark float-start me-3" style="height: 25px; width: 25px;"></div> Inspected, verified and found OK as to quantity and specification
                                        </div>
                                        <div class="w-75 m-auto mt-5 border-top border-dark text-center" style="font-size: 13px;" style="font-size: 13px;">
                                            Inspection Officer / Inspector Committee
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 p-0">
                                    <div class="border-bottom border-dark fw-bold fst-italic fs-5 text-center">
                                        ACCEPTANCE
                                    </div>
                                    <div class="p-5">
                                        <div class="mb-3" style="font-size: 14px;">
                                            Date Received:
                                        </div>
                                        <div class="w-75 m-auto" style="font-size: 14px;">
                                            <div class="border border-dark float-start me-3" style="height: 15px; width: 25px;"></div> Complete
                                        </div>
                                        <div class="w-75 m-auto" style="font-size: 14px;">
                                            <div class="border border-dark float-start me-3" style="height: 15px; width: 25px;"></div> Partial (pls. specify)
                                        </div>
                                        <div class="w-75 m-auto mt-5 border-top border-dark text-center" style="font-size: 13px;">
                                            Supply and/or Property Custodian
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <script>
            window.addEventListener('load', function () {
                window.print();
            })
        </script>
    </body>
</html>