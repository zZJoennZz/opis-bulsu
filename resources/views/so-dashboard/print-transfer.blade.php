<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @if ($transfer->item->transaction->type === "PAR")
    <title>
        Print PTR {{ $transfer->number }} | OPIS - BulSU e-PROCUREMENT
    </title>
    @else
    <title>
        Print ITR {{ $transfer->number }} | OPIS - BulSU e-PROCUREMENT
    </title>
    @endif
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
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="to-print">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-end fst-italic" style="font-size: 10px;">
                    @if ($transfer->item->transaction->type === "PAR")
                    Appendix 76
                    @else
                    Annex A.5
                    @endif
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="text-center fw-bold">
                        <div>
                            @if ($transfer->item->transaction->type === "PAR")
                            PROPERTY TRANSFER REPORT (PTR)
                            @else
                            INVENTORY TRANSFER REPORT (ITR)
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12">
                    <span>
                        Entity Name:
                    </span>
                    <div class="d-inline pe-5 border-bottom border-dark fw-bold text-uppercase">
                        Bulacan State University
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <table class="table table-borderless table-sm">
                        <thead class="align-middle text-center">
                            <tr>
                                <td colspan="4" class="text-start border border-dark">
                                    <div>From Accountable Officer/Agency/Fund Cluster : <div class="d-inline pe-5 border-bottom border-dark">{{
                                            $transfer->issuers[0]->end_user->first_name . ' ' . $transfer->issuers[0]->end_user->middle_name . ' ' .
                                            $transfer->issuers[0]->end_user->last_name }}
                                        </div>
                                    </div>
                                    <div>To Accountable Officer/Agency/Fund Cluster : <div class="d-inline pe-5 border-bottom border-dark">{{
                                            $transfer->receivers[0]->end_user->first_name . ' ' . $transfer->receivers[0]->end_user->middle_name . ' '
                                            .
                                            $transfer->receivers[0]->end_user->last_name }}
                                        </div>
                                    </div>
                                </td>
                                <td colspan="2" class="text-start border border-dark">
                                    <div>
                                        @if ($transfer->item->transaction->type === "PAR")
                                        PTR No. :
                                        @else
                                        ITR No. :
                                        @endif
                                        <div class="d-inline pe-4 border-bottom border-dark">{{ $transfer->number }}</div>
                                    </div>
                                    <div>Date : <div class="d-inline pe-4 border-bottom border-dark">{{ date('Y-m-d',
                                            strtotime($transfer->created_at)) }}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-start border border-dark">
                                    <div>Transfer Type: (check only one)</div>
                                    <div class="m-auto w-75 row">
                                        <div class="col-6">
                                            <div class="mb-1">
                                                <div class="d-inline border border-dark px-4 me-2">
                                                    @if ($transfer->type === "DONATION")
                                                    <em class="bi bi-check-lg position-absolute"></em>
                                                    @endif
                                                </div>
                                                Donation
                                            </div>
                                            <div>
                                                <div class="d-inline border border-dark px-4 me-2">@if ($transfer->type === "REASSIGNMENT")
                                                    <em class="bi bi-check-lg position-absolute"></em>
                                                    @endif
                                                </div> Reassignment
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mb-1">
                                                <div class="d-inline border border-dark px-4 me-2">@if ($transfer->type === "RELOCATE")
                                                    <em class="bi bi-check-lg position-absolute"></em>
                                                    @endif
                                                </div> Relocate
                                            </div>
                                            <div>
                                                <div class="d-inline border border-dark px-4 me-2">@if ($transfer->type === "OTHERS")
                                                    <em class="bi bi-check-lg position-absolute"></em>
                                                    @endif
                                                </div> Others (Specify) @if ($transfer->type === "OTHERS")
                                                <div class="d-inline pe-5 border-bottom border-dark">
                                                    {{ $transfer->other_type }}
                                                </div>
                                                @else
                                                _____________
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                @if ($transfer->item->transaction->type === "PAR")
                                <td class="border border-dark" style="width: 15%;">Date Acquired</td>
                                <td class="border border-dark" style="width: 20%;" colspan="2">Property No.</td>
                                @else
                                <td class="border border-dark" style="width: 10%;">Date Acquired</td>
                                <td class="border border-dark" style="width: 10%;">Item No.</td>
                                <td class="border border-dark" style="width: 15%;">ICS No./Date</td>
                                @endif
                                <td class="border border-dark" style="width: 40%;">Description</td>
                                <td class="border border-dark" style="width: 15%;">Amount</td>
                                <td class="border border-dark" style="width: 10%;">
                                    @if ($transfer->item->transaction->type === "PAR")
                                    Condition of PPE
                                    @else
                                    Condition of Inventory
                                    @endif
                                </td>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @if ($transfer->item->transaction->type === "PAR")
                            <tr>
                                <td class="border-start border-dark">{{ date('Y-m-d', strtotime($transfer->item->transaction->date_acquired)) }}</td>
                                <td class="border-start border-dark" colspan="2">{{ $transfer->item->property_number }}</td>
                                <td class="border-start border-dark">{{
                                    $transfer->item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }} SN: {{
                                    $transfer->property->property->serial_number }}</td>
                                <td class="border-start border-dark">₱ {{ number_format($transfer->item->bac_reso_item->quotation->offered_unit_price,
                                    2) }}</td>
                                <td class="border-start border-end border-dark">{{ $transfer->property->property->property_condition }}</td>
                            </tr>
                            @for ($i = 0; $i < 14; $i++) <tr class="text-light">
                                <td class="border-start border-dark">-</td>
                                <td class="border-start border-dark" colspan="2"></td>
                                <td class="border-start border-dark"></td>
                                <td class="border-start border-dark"></td>
                                <td class="border-start border-end border-dark"></td>
                                </tr>
                                @endfor
                                @else
                                <tr>
                                    <td class="border-start border-dark">{{ date('Y-m-d',
                                        strtotime($transfer->item->transaction->date_acquired)) }}</td>
                                    <td class="border-start border-dark">{{ $transfer->item->property_number }}</td>
                                    <td class="border-start border-dark">{{ $transfer->item->transaction->number }}</td>
                                    <td class="border-start border-dark">{{
                                        $transfer->item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }} SN: {{
                                        $transfer->property->property->serial_number }}</td>
                                    <td class="border-start border-dark">₱ {{
                                        number_format($transfer->item->bac_reso_item->quotation->offered_unit_price,
                                        2) }}</td>
                                    <td class="border-start border-end border-dark">{{ $transfer->property->property->property_condition }}</td>
                                </tr>
                                @for ($i = 0; $i < 14; $i++) <tr class="text-light">
                                    <td class="border-start border-dark">-</td>
                                    <td class="border-start border-dark"></td>
                                    <td class="border-start border-dark"></td>
                                    <td class="border-start border-dark"></td>
                                    <td class="border-start border-dark"></td>
                                    <td class="border-start border-end border-dark"></td>
                                    </tr>
                                    @endfor
                                    @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6" class="border border-dark">
                                    @if ($transfer->item->transaction->type === 'PAR')
                                    <div class="fw-bold">Reason for Transfer:</div>
                                    <div class="w-75 m-auto border-bottom border-dark">
                                        {{ $transfer->reason }}
                                    </div>
                                    @else
                                    <table style="width: 90%;">
                                        <tr>
                                            <td>Reason/s for Transfer:</td>
                                            <td style="width: 80%;" class="border-bottom border-dark">{{ $transfer->reason }}</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td style="width: 80%;" class="border-bottom border-dark text-light">-</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td style="width: 80%;" class="border-bottom border-dark text-light">-</td>
                                        </tr>
                                    </table>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6" class="border border-dark">
                                    <table class="w-100">
                                        <tr>
                                            <td style="width: 10%;"></td>
                                            <td style="width: 22.5%;" class="text-center fw-bold">Approved by:</td>
                                            <td style="width: 22.5%;" class="text-center fw-bold">Released / Issued by:</td>
                                            <td style="width: 22.5%;" class="text-center fw-bold">Received by:</td>
                                        </tr>
                                        <tr>
                                            <td>Signature:</td>
                                            <td colspan="3"></td>
                                        </tr>
                                        <tr>
                                            <td>Printed Name:</td>
                                            <td class="fw-bold text-center">ELIZABETH B. SUNGA</td>
                                            <td class="fw-bold text-uppercase text-center">{{ $transfer->issuers[0]->end_user->first_name . ' ' .
                                                $transfer->issuers[0]->end_user->middle_name . ' ' . $transfer->issuers[0]->end_user->last_name }}
                                            </td>
                                            <td class="fw-bold text-uppercase text-center border-bottom border-dark">{{
                                                $transfer->receivers[0]->end_user->first_name . ' ' .
                                                $transfer->receivers[0]->end_user->middle_name . ' ' . $transfer->receivers[0]->end_user->last_name }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Designation:</td>
                                            <td class="fw-bold text-center">OIC of the Asst.Dir. of the AMU</td>
                                            <td class="fw-bold text-center">{{ $transfer->issuers[0]->end_user->position->name }}, {{
                                                $transfer->issuers[0]->end_user->branch->branch_name }}
                                            </td>
                                            <td class="fw-bold text-center border-bottom border-dark">{{
                                                $transfer->receivers[0]->end_user->position->name }}, {{
                                                $transfer->receivers[0]->end_user->branch->branch_name }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Date:</td>
                                            <td class="fw-bold text-center border-bottom border-dark"></td>
                                            <td class="fw-bold text-center border-bottom border-dark">
                                            </td>
                                            <td class="fw-bold text-center border-bottom border-dark">
                                            </td>
                                        </tr>
                                    </table>
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