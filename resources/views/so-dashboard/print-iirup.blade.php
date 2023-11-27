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
                    margin: 10px 0px 0px 10px;
                    page-break-after: always;
                }
            }
        </style>
    </head>
    <body>
        <div class="to-print" id="print-abs">
            <div class="container-fluid" style="position: relative;">
                <div class="row">
                    <div class="col-12">
                        <div style="position: absolute; top: 0; right: 1rem;" class="text-end">
                            <div class="fw-bold">BAC-II Resolution No.</div>
                            <div class="fw-bold">P.R. No.</div>
                        </div>
                    </div>
                    <div class="col-7 m-auto">
                        <img src="{{ asset('img/bsu-small-logo.png') }}" alt="BSU Logo" style="position: absolute; top: 0; left: 20vw; width: 75px;" />
                        <div class="text-center">
                            <div>Republic of the Philippines</div>
                            <div class="fw-bold">BULACAN STATE UNIVERSITY</div>
                            <div class="fw-bold text-uppercase"><span style="font-size: 14px;">B</span>ids and <span style="font-size: 14px;">A</span>wards <span style="font-size: 14px;">C</span>ommittee for <span style="font-size: 14px;">G</span>oods and <span style="font-size: 14px;">S</span>ervices</div>
                            <div class="fst-italic">City of Malolos, Bulacan</div>
                            <div class="fw-bold mt-5">ABSTRACT OF CANVASS AND BAC RESOLUTION</div>
                        </div>
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