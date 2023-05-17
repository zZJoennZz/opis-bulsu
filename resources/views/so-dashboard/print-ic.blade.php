<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>
            Inventory Custodian Slip Print
        </title>
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        <link rel="manifest" href="{{ asset('site.webmanifest') }}" crossorigin="use-credentials" />
        <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <style>
            * {
                font-family: 'Times New Roman', Times, serif !important;
                font-size: 14px;
            }
        </style>
        <style media="print">
            @media print {
                @page {
                    margin: 5px;
                    page-break-after: always;
                }
            }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 m-auto">
                    <img src="{{ asset('img/bsu-small-logo.png') }}" alt="BSU Logo" style="position: absolute; top: 0; left: 25vw; width: 75px;" />
                    <div class="text-center">
                        <div>Republic of the Philippines</div>
                        <div class="fw-bold">BULACAN STATE UNIVERSITY</div>
                        <div class="fw-bold mt-3 fs-4">Inventory Custodian Slip</div>
                    </div>
                </div>
            </div>
            <div class="row mt-4 mb-3">
                <div class="col-12 fw-bold">
                    Entity Name: Bulacan State University
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-6 fw-bold">
                    Fund Cluster: {{ $ics->source_of_fund->source_of_fund }}
                </div>
                <div class="col-6 text-end fw-bold">
                    ICS No.: {{ $ics->ics_number }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <table class="table table-bordered border-dark">
                        <thead class="text-center">
                            <tr>
                                <th rowspan="2">Quantity</th>
                                <th rowspan="2">Unit</th>
                                <th colspan="2">Amount</th>
                                <th rowspan="2">Description</th>
                                <th rowspan="2">Inventory Item No.</th>
                                <th rowspan="2">Estimated Useful Life</th>
                            </tr>
                            <tr>
                                <th>Unit Cost</th>
                                <th>Total Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalQty = 0;
                                foreach($ics->bac_reso->quotation->pr_item->ppmp->milestones as $mile) {
                                    $totalQty += $mile->milestone_value;
                                }
                            @endphp
                            <tr>
                                <td>{{ $totalQty }}</td>
                                <td>{{ $ics->bac_reso->quotation->pr_item->ppmp->item_detail->unit->uom }}</td>
                                <td>₱ {{ number_format($ics->bac_reso->quotation->offered_unit_price, 2) }}</td>
                                <td>₱ {{ number_format($totalQty * $ics->bac_reso->quotation->offered_unit_price, 2) }}</td>
                                <td>{{ $ics->bac_reso->quotation->pr_item->ppmp->item_detail->description }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-6 border border-dark p-5">
                        <div class="fw-bold mb-4">Received from:</div>
                        <div class="text-center">{{ $ics->received_from_user->first_name }} {{ $ics->received_from_user->middle_name }} {{ $ics->received_from_user->last_name }}</div>
                        <div class="fw-bold mb-4 border-top border-dark text-center">Signature Over Printed Name</div>
                        <div class="text-center">{{ $ics->received_from_user->position->name }}</div>
                        <div class="fw-bold mb-4 border-top border-dark text-center">Position / Office</div>
                        <div class="fw-bold border-top border-dark text-center">Date</div>
                    </div>
                    <div class="col-6 border-top border-end border-bottom border-dark p-5">
                        <div class="fw-bold mb-4">Received by:</div>
                        <div class="text-center">{{ $ics->received_by_user->first_name }} {{ $ics->received_by_user->middle_name }} {{ $ics->received_by_user->last_name }}</div>
                        <div class="fw-bold mb-4 border-top border-dark text-center">Signature Over Printed Name</div>
                        <div class="text-center">{{ $ics->received_by_user->position->name }}</div>
                        <div class="fw-bold mb-4 border-top border-dark text-center">Position / Office</div>
                        <div class="fw-bold border-top border-dark text-center">Date</div>
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