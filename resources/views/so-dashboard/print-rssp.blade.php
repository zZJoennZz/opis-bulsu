<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>
            Print Receipt of Returned Semi-Expendable Property |  OPIS - BulSU e-PROCUREMENT
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
                    size: A4 portrait;
                    margin: 10px 0px 0px 10px;
                    page-break-after: always;
                }
            }
        </style>
    </head>
    <body>
        @php
            $firstRecord = $groupedPropertyHistories->first()[0];
        @endphp
        <div class="to-print">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <table class="w-100">
                            <tbody>
                                <tr>
                                    <td class="fw-bold border border-dark" colspan="5">
                                        <div class="text-end small">Annex A.6</div>
                                        <div class="pt-1 text-uppercase text-center fs-5">Receipt of Returned Semi-Expendable Property (RSSP)</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td rowspan="2" colspan="3" class="border border-dark py-3">
                                        Entity Name: <span class="fw-bold text-uppercase text-decoration-underline">Bulacan State University</span>
                                    </td>
                                    <td colspan="2" class="border border-dark">
                                        Date: <span class="fw-bold text-decoration text-decoration-underline">{{ date('Y-m-d', strtotime($firstRecord->created_at)) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="border border-dark">
                                        RRSP No.: <span class="fw-bold text-decoration text-decoration-underline">{{ $firstRecord->record_number }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 40%;" class="fw-bold border border-dark text-center">Item Description</td>
                                    <td style="width: 10%;" class="fw-bold border border-dark text-center">Quantity</td>
                                    <td style="width: 15%;" class="fw-bold border border-dark text-center">ICS/PAR No.</td>
                                    <td style="width: 20%;" class="fw-bold border border-dark text-center">End-user</td>
                                    <td style="width: 15%;" class="fw-bold border border-dark text-center">Remarks</td>
                                </tr>
                                @foreach ($groupedPropertyHistories as $history)
                                    @php
                                        $hist = $history[0];
                                        $decodedDetails = collect(json_decode($hist->details, 1));
                                    @endphp
                                    <tr>
                                        <td class="border border-dark">
                                            {{ $decodedDetails["property_detail"]["item"]["bac_reso_item"]["quotation"]["pr_item"]["ppmp"]["item_detail"]["description"] }}, {{ $decodedDetails["property_detail"]["item"]["bac_reso_item"]["quotation"]["brand_and_model_offered"] }}
                                            {{-- , S/N: {{ $decodedDetails["property_detail"]["serial_number"] }} --}}
                                        </td>
                                        <td class="border border-dark">
                                            {{ count($history) }}
                                        </td>
                                        <td class="border border-dark">
                                            {{ $hist->property->item->transaction->number }}
                                        </td>
                                        <td class="border border-dark">
                                            {{ $decodedDetails["property_detail"]["current_owners"][0]["end_user"]["first_name"] }} {{ $decodedDetails["property_detail"]["current_owners"][0]["end_user"]["middle_name"] }} {{ $decodedDetails["property_detail"]["current_owners"][0]["end_user"]["last_name"] }}
                                        </td>
                                        <td class="border border-dark">
                                            @for ($i = 0; $i < count($history); $i++)
                                                {{ collect(json_decode($history[$i]->details, 1))["remarks"] }}
                                                @if ($i != count($history) - 1)
                                                    /
                                                @endif
                                            @endfor
                                        </td>
                                    </tr>
                                @endforeach
                                @for ($i = 0; $i < 19 - count($groupedPropertyHistories); $i++)
                                <tr>
                                    <td class="border border-dark text-light">-</td>
                                    <td class="border border-dark"></td>
                                    <td class="border border-dark"></td>
                                    <td class="border border-dark"></td>
                                    <td class="border border-dark"></td>
                                </tr>
                                @endfor

                                <tr>
                                    <td class="border border-dark fst-italic text-center">*** Nothing follows ***</td>
                                    <td class="border border-dark"></td>
                                    <td class="border border-dark"></td>
                                    <td class="border border-dark"></td>
                                    <td class="border border-dark"></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="border py-3 border-dark">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="mb-4">Returned by:</div>
                                                @php
                                                    $decodedDetails = json_decode($firstRecord->details);
                                                @endphp
                                                <div class="w-100 border-bottom border-dark text-center">{{ $decodedDetails->property_detail->current_owners[0]->end_user->first_name }} {{ $decodedDetails->property_detail->current_owners[0]->end_user->middle_name }} {{ $decodedDetails->property_detail->current_owners[0]->end_user->last_name }}</div>
                                                <div class="w-100 text-center"><strong>End-User</strong> <em>(Signature over Printed Name)</em></div>
                                                <div class="w-100 border-bottom border-dark text-center">{{ date('Y-m-d', strtotime($firstRecord->created_at)) }}</div>
                                                <div class="w-100 text-center">Date</div>
                                            </div>
                                            <div class="col-4"></div>
                                            <div class="col-4">
                                                <div class="mb-4">Received by:</div>
                                                <div class="w-100 border-bottom border-dark text-center">{{ $decodedDetails->signatories->noted_by->name }}</div>
                                                <div class="w-100 text-center">{{ $decodedDetails->signatories->noted_by->designation }}</div>
                                                <div class="w-100 border-bottom border-dark text-center">{{ date('Y-m-d', strtotime($firstRecord->created_at)) }}</div>
                                                <div class="w-100 text-center">Date</div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
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