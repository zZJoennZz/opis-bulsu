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

            @media print {
                @page {
                    size: A4 portrait;
                    margin: 10px 10px 0px 10px;
                    page-break-after: always;
                }
            }
        </style>
    </head>
    <body>
        <div class="to-print" id="print-abs">
            <div class="container-fluid" style="position: relative;">
                <div class="row mb-5">
                    <div class="col-7 m-auto">
                        <img src="{{ asset('img/bsu-small-logo.png') }}" alt="BSU Logo" style="position: absolute; top: 0; left: 20vw; width: 75px;" />
                        <div class="text-center">
                            <div>Republic of the Philippines</div>
                            <div class="fw-bold mb-3">BULACAN STATE UNIVERSITY</div>
                            <div class="fw-bold text-uppercase mt-5">Report of Maintenance Unit</div>
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
        <script>
            window.addEventListener('load', function () {
                window.print();
            })
        </script>
    </body>
</html>