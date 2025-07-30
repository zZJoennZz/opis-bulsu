<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>
            Purchase Request {{ $pr->pr_number }}
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
                    size: A4 portrait;
                    margin: 5px 0px 0px 5px;
                }
            }
        </style>
    </head>
    <body>
        <div style="width: 98%;" class="m-auto small">
            <div class="text-end w-100 fst-italic mb-2">Appendix 60 of GAM</div>
            <table class="w-100">
                {{-- <thead class="border-bottom border-dark">
                </thead> --}}
                <tbody class="border border-dark">
                    <tr>
                        <th colspan="6">
                            <div class="row mb-5">
                                <div class="col-12">
                                    <div class="fw-bold fs-4 text-center">
                                        Purchase Request
                                    </div>
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr class="border-bottom border-dark">
                        <th style="font-weight: normal !important;">Entity Name:</th>
                        <th colspan="2">{{ $pr->pr_items[0]->ppmp->branch->branch_name }}</th>
                        <th style="font-weight: normal !important;">Fund Cluster:</th>
                        <th colspan="2">{{ $pr->pr_items[0]->ppmp->source_of_fund->source_of_fund }}</th>
                    </tr>
                    <tr class="border-dark border-end border-start">
                        <th colspan="2" class="border-end border-bottom border-dark p-1">
                            <div class="text-start fw-normal">Office/Section:</div>
                            <div class="text-center fs-5">
                                {{ $pr->office }}
                            </div>
                        </th>
                        <th colspan="2" class="border-end border-bottom border-dark">
                            <div class="w-100 d-flex">
                                <div style="width: 50%;">PR No.:</div> <div class="border-bottom border-dark" style="width: 50%;">{{ $pr->pr_number }}</div>
                            </div>
                            <div class="w-100 d-flex">
                                <div style="width: 50%;">Responsibility Center Code:</div> <div class="border-bottom border-dark" style="width: 50%;">{{ $pr->responsibility_center_code }}</div>
                            </div>
                        </th>
                        <th colspan="2" class="border-end border-bottom border-dark">
                            <div class="w-100 d-flex">
                                <div style="width: 30%;">Date:</div> <div class="border-bottom border-dark" style="width: 60%;">{{ date('Y-m-d', strtotime($pr->created_at)) }}</div>
                            </div>
                        </th>
                    </tr>
                    <tr class="border-dark border-bottom border-end border-start text-center">
                        <th style="width: 10%;" class="border-end border-dark">Stock/<br />Property No.</th>
                        <th style="width: 10%;" class="border-end border-dark">Unit</th>
                        <th style="width: 30%;" class="border-end border-dark">Description</th>
                        <th style="width: 16.66%;" class="border-end border-dark">Quantity</th>
                        <th style="width: 16.66%;" class="border-end border-dark">Unit Cost</th>
                        <th style="width: 16.66%;" class="border-end border-dark">Total Cost</th>
                    </tr>
                    @php
                        $ctr = 1;
                    @endphp
                    @foreach ($pr->pr_items as $item)
                        <tr class="border-bottom border-dark">
                            @php
                                $totalQty = 0;
                                foreach($item->ppmp->milestones as $m) {
                                    $totalQty += $m->milestone_value;
                                }
                            @endphp
                            <td class="border-end border-dark text-center">{{ $ctr }}</td>
                            <td class="border-end border-dark text-center">{{ $item->ppmp->item_detail->unit->uom }}</td>
                            <td class="border-end border-dark">{{ $item->ppmp->item_detail->description }}</td>
                            <td class="border-end border-dark text-center">{{ $totalQty }}</td>
                            <td class="border-end border-dark text-center">{{ number_format($item->ppmp->item_detail->price_catalogue, 2) }}</td>
                            <td class="text-center text-center">{{ number_format($item->ppmp->item_detail->price_catalogue * $totalQty, 2) }}</td>
                        </tr>
                        @php
                            $ctr += 1;
                        @endphp
                    @endforeach
                    <tr>
                        <td colspan="6" class="border-bottom border-dark">
                            <div class="d-flex p-1">
                                <div class="w-25">Purpose:</div>
                                <div class="w-75 border-bottom border-dark">{{ $pr->purpose }}</div>
                            </div>
                        </td>
                    </tr>
                    <tr class="border-bottom border-dark">
                        <td colspan="3" class="border-end border-dark p-3">
                            <div class="fw-bold mb-3 text-center">
                                Requested by:
                            </div>
                            <div style="font-size: 11px !important;">
                                <div class="d-flex">
                                    <div class="w-25 text-end fw-bold">Signature:</div> <div class="border-bottom border-dark w-75"></div>
                                </div>
                                <div class="d-flex">
                                    <div class="w-25 text-end fw-bold">Printed Name:</div> <div class="border-bottom border-dark w-75 text-center">{{ $pr->requester->profile->first_name }} {{ $pr->requester->profile->last_name }}</div>
                                </div>
                                <div class="d-flex">
                                    <div class="w-25 text-end fw-bold">Designation:</div> <div class="w-75 text-center" style="font-size: 10px !important;">{{ $pr->requester->profile->position->description }}</div>
                                </div>
                            </div>
                        </td>
                        <td colspan="3">
                            <div class="mb-3 text-center">
                                Approved
                            </div>
                            <div style="font-size: 11px !important;">
                                <div class="pt-3 text-center fw-bold">{{ getSettingValue("university_president") }}</div>
                                <div class="text-center">
                                    University President
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
                {{-- <tfoot class="border-start border-end border-dark" style="padding: 0px !important;">
                    
                </tfoot> --}}
            </table>
            <div class="row mt-3">
                <div class="col-12 fst-italic mb-3">
                    To be accomplished by the Procurement Office:
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="row">
                        <div class="col-4">
                            Included in the:
                        </div>
                        <div class="col-8">
                            <div>☐ 2024 Annual Procurement Plan</div>
                            <div>☐ 2024 Supplemental PPMP</div>
                            <div>☐ 2024 Revised PPMP</div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="row">
                        <div class="col-6">
                            <div>Item No.:____ Page No.____</div>
                            <div>Item No.:____ Page No.____</div>
                            <div>Item No.:____ Page No.____</div>
                        </div>
                        <div class="col-6" style="font-size: 10px !important;">
                            PROCUREMENT OFFICER
                        </div>
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