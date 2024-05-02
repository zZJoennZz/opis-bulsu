<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>
            Request for Quotation Print
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
                <div class="col-12 border border-dark text-center">
                    <img src="{{ asset('img/bsu-small-logo.png') }}" alt="BSU Logo" style="position: absolute; margin-top: 10px; left: 20vw; width: 75px;" />
                    <div class="mt-1 fs-5 fw-bold">
                        Republic of the Philippines
                    </div>
                    <div class="fs-5 text-uppercase">
                        BULACAN STATE UNIVERSITY
                    </div>
                    <div class="fs-5 fw-bold">
                        City of Malolos, Bulacan
                    </div>
                    <div class="fs-5 text-uppercase">
                        Procurement Office
                    </div>
                    <div class="w-100 py-2">
                        <div class="float-end w-25 text-start">
                            <div class="row p-0">
                                <div class="col-6">QUOTATION NO.</div>
                                <div class="col-6 border-bottom border-dark">{{ $rfq->quotation_number }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 fs-4 fw-bold">
                        REQUEST FOR QUOTATION
                    </div>
                    <div style="font-size: 13px;" class="pb-1">
                        Tel./FAX (004) 789-7755 | Email: procurement@bulsu.edu.ph
                    </div>
                </div>
            </div>
            <div class="row border-bottom border-dark">
                <div class="col-12 text-start border-start border-end border-dark py-1">
                    <div class="row">
                        <div class="col-12">
                            <div class="fw-bold">
                                I. INSTRUCTIONS TO BIDDERS
                            </div>
                            <div>
                                1. Please indicate the following information in your bid
                            </div>
                        </div>
                        <div class="col-6">
                            <div>
                                a. Company name, Address, Contact No., TIN, E-Address and delivery Period
                            </div>
                            <div class="ms-3">
                                Bank name and Account no.
                            </div>
                            <div>
                                b. Bidder's offer/warranty period (technical specifications / brand) per item
                            </div>
                        </div>
                        <div class="col-6">
                            <div>
                                c. Unit Price, Total Price and Total Amount
                            </div>
                            <div>
                                d. Name of Bidder's Authorized Representative
                            </div>
                            <div>
                                e. Signature and Date
                            </div>
                        </div>
                        <div class="col-12">
                            2. All Mandatory <span class="fst-italic" style="color: red;">with asterisk (*)#4</span> must be complied with. Failure to comply with the mandatory requirements shall render the quotation ineligible / disqualified.
                        </div>
                    </div>
                </div>
            </div>
            <div class="row border-bottom border-start border-end border-dark">
                <div class="col-12 text-danger fst-italic">
                    Other instruction and Terms and Conditions please see it at the back of this page.
                </div>
            </div>
            <div class="row border-bottom border-start border-end border-dark">
                <div class="col-12 fw-bold">
                    Note: BulSU reserves the right to accept or reject any or all of the quotations to waive formally therein, or to accept quotation/s as may be considered most advantageous to the government or to pursue appropriate legal action should the winning bidder refuse to accept the award without justifiable reason/s
                </div>
            </div>
            <div class="row border-bottom border-start border-end border-dark">
                <div class="col-12 fw-bold">
                    II. ELIGIBILITY AND OTHER REQUIREMENTS
                </div>
                <div class="col-12 fw-bold">
                    Suppliers are required to submit the following documents simultaneous with submit of bid offer/s
                </div>
                <div class="col-12">
                    1. Valid and Current Mayor's / Business Permit
                </div>
                <div class="col-12">
                    2. Valid and Current PhilGEPS Registration Certificate / Number
                </div>
            </div>
        </div>
        {{-- <script>
            window.addEventListener('load', function () {
                window.print();
            })
        </script> --}}
    </body>
</html>