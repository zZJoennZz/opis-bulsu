<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>
            PRINT Consolidated Procurement |  OPIS - BulSU e-PROCUREMENT
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
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="text-center">
                            <img src="{{ asset('img/bsu-small-logo.png') }}" alt="BSU Logo" style="position: absolute; top: 0; left: 20vw; width: 75px;" />
                            <div>Republic of the Philippines</div>
                            <div class="fw-bold">BULACAN STATE UNIVERSITY</div>
                            <div class="fst-italic">City of Malolos, Bulacan</div>
                            <div class="fw-bold mt-5">Consolidated Annual Procurement Plan {{ Auth::user()->ppmp_year }}</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <table class="table table-bordered border-dark table-sm">
                            <thead class="align-middle text-center">
                                <tr>
                                    <th style="width: 45%;">Item Detail</th>
                                    <th class="text-center" style="width: 10%;">Unit</th>
                                    <th class="text-center" style="width: 15%;">Qty</th>
                                    <th class="text-center" style="width: 15%;">Price Catalogue</th>
                                    <th class="text-center" style="width: 15%;">Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($grandTotalAmt = 0)
                                @foreach ($consolidated_records as $record)
                                    <tr>
                                        <td>{{ $record->description }}</td>
                                        <td class="text-center">{{ $record->unit->uom }}</td>
                                        <td class="text-center">
                                            @php($totalQty = 0)
                                            @foreach ($record->ppmp as $ppmp)
                                                @foreach ($ppmp->milestones as $milestone)
                                                    @php($totalQty += $milestone->milestone_value)
                                                @endforeach
                                            @endforeach
                                            {{ $totalQty }}
                                        </td>
                                        <td class="text-end">
                                            <div class="float-start">₱</div>
                                            {{ number_format($record->price_catalogue, 2) }}
                                            {{-- {{ number_format(json_decode($record)->price_catalogue, 2) }} --}}
                                        </td>
                                        <td class="text-end">
                                            <div class="float-start">₱</div>
                                            {{ number_format($record->price_catalogue * $totalQty, 2) }}
                                            @php($grandTotalAmt += floatval($record->price_catalogue * $totalQty)) 
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="fs-4 fw-bold">
                                    <td class="text-end" colspan="4">Grand Total</td>
                                    <td class="text-end">
                                        <div class="float-start">₱</div>
                                        {{ number_format($grandTotalAmt, 2) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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