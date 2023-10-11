<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>
            Print Maintenance Request |  OPIS - BulSU e-PROCUREMENT
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

            /* @if ($printMaintenance->type === "MAINTENANCE")
                @media print {
                    @page {
                        size: A4 portrait;
                        margin: 10px 10px 0px 10px;
                        page-break-after: always;
                    }
                }
            @endif

            @if ($printMaintenance->type === "DISPOSE")
                @media print {
                    @page {
                        size: A4 landscape;
                        margin: 0;
                        page-break-after: always;
                    }
                }
            @endif */

            @media print {
                @page {
                    size: A4 landscape;
                    margin: 0;
                    page-break-after: always;
                }
            }
        </style>
    </head>
    <body>
        @if ($printMaintenance->type === "DISPOSE")
        <div class="to-print" id="print-abs">
            <div style="position: relative;" class="container-fluid">
                <div class="row mb-5">
                    <div class="col-7 m-auto">
                        <img src="{{ asset('img/bsu-small-logo.png') }}" alt="BSU Logo" style="position: absolute; top: 0; left: 20vw; width: 75px;" />
                        <div class="text-center">
                            <div>Republic of the Philippines</div>
                            <div class="fw-bold mb-3">BULACAN STATE UNIVERSITY</div>
                            <div class="fw-bold text-uppercase mt-5">Summary of Unserviceable Property</div>
                        </div>
                    </div>
                </div>
                <div class="row ps-2 pe-2">
                    <div class="col-9">
                        <div class="row mb-3">
                            <div class="col-2 fw-bold">Entity Name:</div>
                            <div class="col-10">
                                <div class="border-bottom border-dark w-50">Bulacan State University</div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-4">
                                <div class="w-100 m-auto text-center border-bottom border-dark">{{ $printMaintenance->property->current_owners[0]->end_user->first_name }} {{ $printMaintenance->property->current_owners[0]->end_user->middle_name }} {{ $printMaintenance->property->current_owners[0]->end_user->last_name }}</div>
                                <div class="text-center small mb-3">(Name of Accountable Officer)</div>
                            </div>
                            <div class="col-4">
                                <div class="w-100 m-auto text-center border-bottom border-dark">{{ $printMaintenance->property->current_owners[0]->end_user->position->name }}</div>
                                <div class="text-center small mb-3">(Designation)</div>
                            </div>
                            <div class="col-4">
                                <div class="w-100 m-auto text-center border-bottom border-dark">{{ $printMaintenance->property->current_owners[0]->end_user->branch->branch_name }}</div>
                                <div class="text-center small mb-3">(Station)</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="row">
                            <div class="col-5 fw-bold">Fund Cluster:</div>
                            <div class="col-7">
                                <div class="border-bottom border-dark w-100 text-light">-</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row ps-2 pe-2">
                    <table class="table table-sm table-bordered border-dark">
                        <thead class="text-center">
                            <tr>
                                <th colspan="10">INVENTORY</th>
                                <th colspan="8">INSPECTION and DISPOSAL</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <tr>
                                <td rowspan="2" class="align-middle">Date Acquired</td>
                                <td rowspan="2" class="align-middle">Particulars/ Articles</td>
                                <td rowspan="2" class="align-middle">Property No.</td>
                                <td rowspan="2" class="align-middle">Qty</td>
                                <td rowspan="2" class="align-middle">Unit Cost</td>
                                <td rowspan="2" class="align-middle">Total Cost</td>
                                <td rowspan="2" class="align-middle">Accumulated Depreciation</td>
                                <td rowspan="2" class="align-middle">Accumulated Impairment Losses</td>
                                <td rowspan="2" class="align-middle">Carrying Amount</td>
                                <td rowspan="2" class="align-middle">Remarks</td>
                                <td colspan="5" class="text-uppercase" class="align-middle">Disposal</td>
                                <td rowspan="2" class="align-middle">Appraised Value</td>
                                <td colspan="2" class="text-uppercase" class="align-middle">Records of sales</td>
                            </tr>
                            <tr>
                                <td class="align-middle">Sale</td>
                                <td class="align-middle">Transfer</td>
                                <td class="align-middle">Destruction</td>
                                <td class="align-middle">Others (Specify)</td>
                                <td class="align-middle">Total</td>
                                <td class="align-middle">OR No.</td>
                                <td class="align-middle">Amount</td>
                            </tr>
                            <tr>
                                <td>{{ $printMaintenance->property->item->transaction->date_acquired }}</td>
                                <td>
                                    {{ $printMaintenance->property->item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}, {{ $printMaintenance->property->item->bac_reso_item->quotation->brand_and_model_offered }}, S/N: {{ $printMaintenance->property->serial_number }}
                                </td>
                                <td>{{ $printMaintenance->property->item->property_number }}</td>
                                <td>1</td>
                                <td>₱ {{ number_format($printMaintenance->property->item->unit_price, 2) }}</td>
                                <td>₱ {{ number_format($printMaintenance->property->item->unit_price * 1, 2) }}</td>
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
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="10" class="p-2">
                                    <div class="row mb-2">
                                        <div class="col-12">I HEREBY request inspection and disposition, pursuant to Section  79 of PD 1445, of the property enumerated above.</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div>Requested by:</div>
                                            <div class="w-75 m-auto text-center border-bottom border-dark">ASDASD</div>
                                            <div class="text-center small mb-3">(Signature over Printed Name of Accountable Officer)</div>
                                            <div class="w-75 m-auto text-center border-bottom border-dark">Supervising Administrative Officer - Supply</div>
                                            <div class="text-center small mb-3">(Designation of Accountable Officer)</div>
                                        </div>
                                        <div class="col-6">
                                            <div>Approved by:</div>
                                            <div class="w-75 m-auto text-center border-bottom border-dark">ASDASD</div>
                                            <div class="text-center small mb-3">(Signature over Printed Name of Authorized Official)</div>
                                            <div class="w-75 m-auto text-center border-bottom border-dark">University President</div>
                                            <div class="text-center small mb-3">(Designation of Authorized Official)</div>
                                        </div>
                                    </div>
                                </td>
                                <td colspan="8" class="p-2">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="mb-4" style="text-align: justify;">I CERTIFY that I have inspected each and every article enumerated in this report, and that the disposition made thereof was, in my judgment, the best for the public interest.</div>
                                            <div>
                                                <div class="w-75 m-auto text-center border-bottom border-dark">ASDASD</div>
                                                <div class="text-center small mb-3">Chairman, Disposal Committee</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mb-5" style="text-align: justify;">I CERTIFY that I have witnessed the disposition of the articles enumerated on this report this ____day of _____________, _____.</div>
                                            <div>
                                                <div class="w-75 m-auto text-center border-bottom border-dark">ASDASD</div>
                                                <div class="text-center small mb-3">BulSU Internal Auditor</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="10" class="pt-5 pb-5">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="w-75 m-auto text-center border-bottom border-dark">ASDASD</div>
                                            <div class="text-center small mb-3">Disposal Committee - Member (ADMIN)</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="w-75 m-auto text-center border-bottom border-dark">ASDASD</div>
                                            <div class="text-center small mb-3">Disposal Committee - Member (ACCOUNTING)</div>
                                        </div>
                                    </div>
                                </td>
                                <td colspan="8">
                                    <div class="ps-2 pe-2" style="text-align: justify;">I CERTIFY that I have witnessed the disposition of the articles enumerated on this report this ____day of _____________, _____.</div>
                                    <div class="w-50 m-auto text-center border-bottom border-dark">ASDASD</div>
                                    <div class="text-center small mb-3">COA Representative</div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        @endif

        @if ($printMaintenance->type === "MAINTENANCE")
        <div class="to-print" id="print-abs">
            <div class="container-fluid" style="position: relative;">
                <div class="row mb-5">
                    <div class="col-7 m-auto">
                        <img src="{{ asset('img/bsu-small-logo.png') }}" alt="BSU Logo" style="position: absolute; top: 0; left: 20vw; width: 75px;" />
                        <div class="text-center">
                            <div>Republic of the Philippines</div>
                            <div class="fw-bold mb-3">BULACAN STATE UNIVERSITY</div>
                            <div class="fw-bold text-uppercase mt-3">Report of Maintenance Unit</div>
                        </div>
                    </div>
                </div>

                <div class="row m-auto w-75 border rounded p-4">
                    <div class="col-12 mb-3">
                        <div class="float-end">
                            Date Created: 2023-09-21
                        </div>
                        <div class="text-uppercase small">Date Acquired</div>
                        <div class="fs-4 fw-bold">2023-09-01</div>
                    </div>

                    <div class="col-12 mb-3">
                        <div class="text-uppercase small">Description</div>
                        <div class="fs-4 fw-bold">Calculator, Compact, Electronic, 12 digits cap, 1 unit in individual box, Serial Number: 001</div>
                    </div>

                    <div class="col-12 mb-3">
                        <div class="text-uppercase small">Amount</div>
                        <div class="fs-4 fw-bold">₱ {{ number_format(234, 2) }}</div>
                    </div>

                    <div class="col-12 mb-3">
                        <div class="text-uppercase small">Cause/Damage</div>
                        <div class="fs-5 fw-bold">Suddenly not functioning, tried replacing battery</div>
                    </div>

                    <div class="col-12">
                        <div class="text-uppercase small">Remarks</div>
                        <div class="fs-5 fw-bold">This was the second time</div>
                    </div>

                    {{-- <table class="table table-sm table-bordered border-dark">
                        <thead class="text-uppercase text-center">
                            <tr>
                                <th style="width: 15%;">Date Acquired</th>
                                <th style="width: 30%;">Description</th>
                                <th style="width: 15%;">Amount</th>
                                <th style="width: 20%;">Cause/Damage</th>
                                <th style="width: 20%;">Remarks</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <tr>
                                <td>2023-09-01</td>
                                <td>Calculator, Compact, Electronic, 12 digits cap, 1 unit in individual box, Serial Number: 001</td>
                                <td>₱ {{ number_format(234, 2) }}</td>
                                <td>Suddenly not functioning, tried replacing battery</td>
                                <td>This was the second time</td>
                            </tr>
                        </tbody>
                    </table> --}}
                </div>
                <div class="mb-5"></div>
                <div class="row m-auto">
                    <div class="col-6 m-auto">
                        <div class="w-75 text-center">
                            <div class="small mb-3">Requested by</div>

                            <div class="border-bottom border-dark w-100">Joenn Aquilino</div>
                            <div class="small">Signature Over Printed Name of Accountable Officer</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="w-75 text-center">
                            <div class="small mb-3">Checked & Verified as to the Record of Accountability</div>

                            <div class="border-bottom border-dark w-100">Juan dela Cruz</div>
                            <div class="small">Signature Over Printed Name</div>
                        </div>
                        <div class="w-75 text-center">
                            <div class="small mb-3">Noted by</div>

                            <div class="border-bottom border-dark w-100">Peter B Parker</div>
                            <div class="small">Head Supply & Property Office</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <script>
            window.addEventListener('load', function () {
                window.print();
            })
        </script>
    </body>
</html>