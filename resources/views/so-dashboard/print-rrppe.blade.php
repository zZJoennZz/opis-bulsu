<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>
            Print Request for Return of Property, Plant & Equipment |  OPIS - BulSU e-PROCUREMENT
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
        <div class="to-print">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-12 d-flex">
                        <img src="{{ asset('img/bsu-small-logo.png') }}" alt="BSU Logo" class="m-auto" style="width: 75px;" />
                    </div>
                    <div class="col-12">
                        <div class="text-center">
                            <div style="font-size: 14px;" class="fw-bold text-uppercase">Request for Return of Property, Plant & Equipment</div>
                        </div>
                    </div>
                </div>

                <div class="row px-2 mb-3">
                    <div class="col-10">
                        <div class="text-end">
                            <div style="font-size: 14px;" class="fw-bold text-uppercase">Date:</div>
                        </div>
                    </div>
                    <div class="col-2 border-bottom border-dark">{{ $propertyHistories[0]->created_at }}</div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <table class="w-100">
                            <tr>
                                <th class="border text-center border-dark" style="width: 15%;">Date Acquired</th>
                                <th class="border text-center border-dark" style="width: 35%;">Description</th>
                                <th class="border text-center border-dark" style="width: 15%;">Amount</th>
                                <th class="border text-center border-dark" style="width: 15%;">Condition of PPE</th>
                                <th class="border text-center border-dark" style="width: 20%;">Remarks</th>
                            </tr>
                            <tbody>
                                @foreach ($propertyHistories as $item)
                                <tr>
                                    <td class="border border-dark">{{ $item->property->item->transaction->date_acquired }}</td>
                                    <td class="border border-dark">{{ json_decode($item->details)->property_detail->item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}, {{  $item->property->item->bac_reso_item->quotation->brand_and_model_offered }}, S/N: {{ json_decode($item->details)->property_detail->serial_number }}</td>
                                    <td class="border border-dark">{{ number_format(json_decode($item->details)->property_detail->item->unit_price, 2) }}</td>
                                    <td class="border border-dark">{{ json_decode($item->details)->property_condition }}</td>
                                    <td class="border border-dark">{{ json_decode($item->details)->remarks }}</td>
                                </tr>
                                @endforeach

                                @for ($i = 0; $i < 19 - count($propertyHistories); $i++)
                                <tr>
                                    <td class="border border-dark text-light">-</td>
                                    <td class="border border-dark"></td>
                                    <td class="border border-dark"></td>
                                    <td class="border border-dark"></td>
                                    <td class="border border-dark"></td>
                                </tr>
                                @endfor

                                <tr>
                                    <td class="border border-dark"></td>
                                    <td class="border border-dark fst-italic text-center">*** Nothing follows ***</td>
                                    <td class="border border-dark"></td>
                                    <td class="border border-dark"></td>
                                    <td class="border border-dark"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 px-5">
                        <div class="text-center mb-5">
                            Requested by:
                        </div>
        
                        @php
                            $decodedDetails = json_decode($propertyHistories[0]->details);
                        @endphp
                        <div class="border-bottom border-dark text-center">{{ $decodedDetails->property_detail->current_owners[0]->end_user->first_name . ' ' . $decodedDetails->property_detail->current_owners[0]->end_user->middle_name . ' ' . $decodedDetails->property_detail->current_owners[0]->end_user->last_name }}</div>
                        <div class="text-center">Signature over Printed Name of Accountable Officer</div>
                    </div>

                    <div class="col-6 px-5">
                        <div class="text-start mb-5">
                            Checked & Verified as to the Record of Accountability:
                        </div>
                        <div class="border-bottom border-dark text-center">{{ $decodedDetails->signatories->verifier }}</div>
                        <div class="text-center mb-3">Signature over Printed Name</div>
                        <div class="text-start mb-5">
                            Noted by:
                        </div>
                        <div class="border-bottom border-dark text-center">{{ $decodedDetails->signatories->noted_by->name }}</div>
                        <div class="text-center">{{ $decodedDetails->signatories->noted_by->designation ?? "Head, Asset Management Unit" }}</div>
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