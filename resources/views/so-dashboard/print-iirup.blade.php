<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>
            Print Inventory and Inspection Report of Unserviceable Property |  OPIS - BulSU e-PROCUREMENT
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
                    margin: 10px 0px 10px 0px;
                    page-break-after: always;
                }
            }
        </style>
    </head>
    <body>
        @php
            $content = json_decode($report->content);
            $content_data = json_decode($content->data);
        @endphp
        <div class="to-print" id="print-abs">
            <div class="container-fluid" style="position: relative;">
                <div class="row mb-3">
                    <div class="col-12">
                        <div style="position: absolute; top: 0; right: 1rem;" class="text-end">
                            <div class="fst-italic">Appendix 74</div>
                        </div>
                    </div>
                    <div class="col-7 m-auto">
                        <img src="{{ asset('img/bsu-small-logo.png') }}" alt="BSU Logo" style="position: absolute; top: 0; left: 20vw; width: 75px;" />
                        <div class="text-center">
                            <div>Republic of the Philippines</div>
                            <div class="fw-bold">BULACAN STATE UNIVERSITY</div>
                            <div class="fst-italic">City of Malolos, Bulacan</div>
                            <div class="fw-bold mt-5">INVENTORY AND INSPECTION REPORT OF UNSERVICEABLE SEMI-EXPENDABLE PROPERTY</div>
                            <div>As at: {{ date('Y-m-d h:mA', strtotime($report->created_at)) }}</div>
                        </div>
                    </div>
                </div>

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-8">
                            <div class="row">
                                <div class="col-2 fw-bold">Entity Name:</div>
                                <div class="col-10 border-bottom border-dark">BULACAN STATE UNIVERSITY</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="row">
                                <div class="col-4 fw-bold">Fund Cluster:</div>
                                <div class="col-8 border-bottom border-dark"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container-fluid mt-2">
                    <div class="row">
                        <div class="col-3">
                            <div class="border-bottom border-dark" style="width: 90%;">{{ $content->end_user->first_name }} {{ $content->end_user->middle_name }} {{ $content->end_user->last_name }}</div>
                            <div class="fst-italic" style="width: 90%;">(Name of Accountable Officer)</div>
                        </div>
                        <div class="col-3">
                            <div class="border-bottom border-dark" style="width: 90%;">{{ $content->end_user->position->name }}</div>
                            <div class="fst-italic" style="width: 90%;">(Designation)</div>
                        </div>
                        <div class="col-3">
                            <div class="border-bottom border-dark" style="width: 90%;">{{ $content->end_user->branch->branch_name }}</div>
                            <div class="fst-italic" style="width: 90%;">(Station)</div>
                        </div>
                        <div class="col-3"></div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <table class="table table-sm table-bordered border-dark">
                            <tbody>
                                <tr>
                                    <th colspan="10" class="text-center" style="width: 60%;">INVENTORY</th>
                                    <th colspan="8" class="text-center" style="width: 40%;">INSPECTION and DISPOSAL</th>
                                </tr>
                                <tr>
                                    <td class="text-center" rowspan="2">Date Acquired</td>
                                    <td class="text-center" rowspan="2">Particulars/Articles</td>
                                    <td class="text-center" rowspan="2">Property No.</td>
                                    <td class="text-center" rowspan="2">Qty</td>
                                    <td class="text-center" rowspan="2">Unit Cost</td>
                                    <td class="text-center" rowspan="2">Total Cost</td>
                                    <td class="text-center" rowspan="2">Accumulated Depreciation</td>
                                    <td class="text-center" rowspan="2">Accumulated Impairment Losses</td>
                                    <td class="text-center" rowspan="2">Carrying Amount</td>
                                    <td class="text-center" rowspan="2">Remarks</td>
                                    <td class="text-center" colspan="5">DISPOSAL</td>
                                    <td class="text-center" rowspan="2">Appraised Value</td>
                                    <td class="text-center" colspan="2">RECORDS OF SALES</td>
                                </tr>
                                <tr>
                                    <td class="text-center">Sale</td>
                                    <td class="text-center">Transfer</td>
                                    <td class="text-center">Destruction</td>
                                    <td class="text-center">Others (Specify)</td>
                                    <td class="text-center">Total</td>
                                    <td class="text-center">OR No.</td>
                                    <td class="text-center">Amount</td>
                                </tr>
                                @foreach ($content_data as $c)
                                    <tr>
                                        <td>{{ $c->item->transaction->date_acquired }}</td>
                                        <td>{{ $c->item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}, {{ $c->item->bac_reso_item->quotation->brand_and_model_offered }}, S/N: {{ $c->serial_number ?? "N/A" }}</td>
                                        <td>{{ $c->item->property_number }}</td>
                                        <td>1</td>
                                        <td>{{ number_format($c->item->bac_reso_item->quotation->offered_unit_price, 2) }}</td>
                                        <td>{{ number_format($c->item->bac_reso_item->quotation->offered_unit_price, 2) }}</td>
                                        <td>{{ $c->accumulated_depreciation }}</td>
                                        <td>{{ $c->accumulated_impairment_losses }}</td>
                                        <td>{{ $c->carrying_amount }}</td>
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
                                @endforeach
                                <tr>
                                    <td colspan="10">
                                        <div class="mt-3 mb-3 row">
                                            <div class="col-12">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I HEREBY request inspection and disposition, pursuant to Section  79 of PD 1445, of the property enumerated above.
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div>Required by:</div>
                                                <div class="text-center w-75 border-bottom border-dark m-auto">{{ $content->end_user->first_name }} {{ $content->end_user->middle_name }} {{ $content->end_user->last_name }}</div>
                                                <div class="text-center mb-2">School Property Custodian</div>
                                                <div class="text-center w-75 border-bottom border-dark m-auto">{{ $content->end_user->position->name }}</div>
                                                <div class="text-center">(Designation of Accountable Officer)</div>
                                            </div>
                                            <div class="col-6">
                                                <div>Approved by:</div>
                                                <div class="text-center w-75 border-bottom border-dark m-auto">{{ getSettingValue("university_president") }}</div>
                                                <div class="text-center mb-2">School Head</div>
                                                <div class="text-center w-75 border-bottom border-dark m-auto">University President</div>
                                                <div class="text-center">(Designation of Accountable Officer)</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td colspan="8">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="mb-5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I CERTIFY that I have inspected each and every article enumerated in this report, and that the disposition made thereof was, in my judgment, the best for the public interest.</div>
                                                <div class="text-center w-75 border-bottom border-dark m-auto"></div>
                                                <div class="text-center mb-2">Disposal Team</div>
                                            </div>
                                            <div class="col-6">
                                                <div class="mb-5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I CERTIFY that I have witnessed the disposition of the articles enumerated on this report this ____day of _____________, _____.</div>
                                                <div class="text-center w-75 border-bottom border-dark m-auto"></div>
                                                <div class="text-center">(Signature over Printed Name of Witness)</div>
                                                <div class="text-center">COA</div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
        <script>
            // window.addEventListener('load', function () {
            //     window.print();
            // })
        </script>
    </body>
</html>