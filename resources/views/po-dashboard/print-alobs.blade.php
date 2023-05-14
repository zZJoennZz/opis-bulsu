<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>
            Allotment and Obligation Slip Print
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
            <div class="mb-3 text-end fs-4 fst-italic">
                Appendix 14
            </div>
            <div class="row">
                <div class="col-8 border border-dark text-center">
                    <div class="mt-4 fs-4 fw-bold text-uppercase">
                        {{ $alobs->purchase_order->bac_reso->abstract_of_canvass->pr->pr_items[0]->ppmp->source_of_fund->description }} Request and Status
                    </div>
                    <div class="mt-1 fs-5 text-uppercase fw-bold text-decoration-underline">
                        Bulacan State University
                    </div>
                    <div>Entity Name</div>
                </div>
                <div class="col-4 border-top border-end border-bottom border-dark p-0">
                    <div class="mt-5">
                        Serial No.: ____________________
                    </div>
                    <div>
                        Date: ____________________
                    </div>
                    <div class="mb-4">
                        Fund Cluster: ____________________
                    </div>
                </div>
            </div>
            <div class="row border-bottom border-dark">
                <div class="col-2 text-center border-start border-end border-dark py-1">Payee</div>
                <div class="col-10 py-1 border-end border-dark fw-bold">{{ $alobs->purchase_order->company->name }}</div>
            </div>
            <div class="row border-bottom border-dark">
                <div class="col-2 text-center border-start border-end border-dark py-1">Office</div>
                <div class="col-10 py-1 border-end border-dark"></div>
            </div>
            <div class="row border-bottom border-dark">
                <div class="col-2 text-center border-start border-end border-dark py-1">Address</div>
                <div class="col-10 py-1 border-end border-dark">{{ $alobs->purchase_order->company->full_address }}</div>
            </div>
            <div class="row border-bottom border-dark text-center">
                <div class="col-2 border-start border-end border-dark pt-3">Responsibility Center
                </div>
                <div class="col-5 border-end border-dark pt-3">Particulars</div>
                <div class="col-3 border-end border-dark">
                    <div class="row">
                        <div class="col-5 border-end border-dark pt-3">
                            MFO/PAP
                        </div>
                        <div class="col-7">
                            UACS Object Code/ Expenditures
                        </div>
                    </div>
                </div>
                <div class="col-2 border-end border-dark pt-3 text-start">Amount</div>
            </div>
            <div class="row">
                <div class="col-2 border-start border-end border-dark"></div>
                <div class="col-5 border-end border-dark">
                    <div class="my-5">
                        Payment for the "{{ $alobs->purchase_order->bac_reso->abstract_of_canvass->pr->purpose }}"
                        <div>in the amount of.......</div>
                    </div>
                    <div class="my-2">
                        {{ translateToWords($alobs->purchase_order->bac_reso->abstract_of_canvass->abc) }}
                    </div>
                </div>
                <div class="col-3 border-end border-dark">
                    <div class="row h-100">
                        <div class="col-5 border-end border-dark pt-3 text-light h-100">
                            -
                        </div>
                        <div class="col-7">
                            
                        </div>
                    </div>
                </div>
                <div class="col-2 border-end border-dark">
                    <div class="my-5 text-end">
                        {{ number_format($alobs->purchase_order->bac_reso->abstract_of_canvass->abc, 2) }}
                    </div>
                </div>
            </div>
            <div class="row border-dark border-bottom">
                <div class="col-2 border-start border-end border-dark"></div>
                <div class="col-5 border-end border-dark text-center fw-bold">
                    Total
                </div>
                <div class="col-3 border-end border-dark">
                    <div class="row h-100">
                        <div class="col-5 border-end border-dark text-light h-100">
                            -
                        </div>
                        <div class="col-7">
                            
                        </div>
                    </div>
                </div>
                <div class="col-2 border-end border-dark fw-bold">
                    <div class="text-end">
                        <div class="float-start">₱</div> {{ number_format($alobs->purchase_order->bac_reso->abstract_of_canvass->abc, 2) }}
                    </div>
                </div>
            </div>
            <div class="row border-bottom border-dark">
                <div class="col-6 border-start border-end border-dark p-0">
                    <div class="row mb-5">
                        <div class="col-2">
                            <div class="float-start border-bottom border-end border-dark pt-1 ps-1 pe-5 pb-2 fw-bold">A.</div>
                        </div>
                        <div class="col-10">
                            <div class="pt-3">
                                <div><strong>Certified:</strong> Charges to appropriation/budget necessary,</div>
                                <div>lawful and under my direct supervision; and supporting</div>
                                <div>documents valid, proper and legal</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">Signature <div class="float-end">:</div></div>
                        <div class="col-9">
                            <div class="w-100 border-bottom border-dark text-light"> - </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">Printed Name <div class="float-end">:</div></div>
                        <div class="col-9">
                            <div class="w-100 border-bottom border-dark text-center">{{ $alobs->purchase_order->bac_reso->abstract_of_canvass->pr->requester->profile->first_name }} {{ $alobs->purchase_order->bac_reso->abstract_of_canvass->pr->requester->profile->last_name }}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">Position <div class="float-end">:</div></div>
                        <div class="col-9">
                            <div class="w-100 border-bottom border-dark text-center">{{ $alobs->purchase_order->bac_reso->abstract_of_canvass->pr->requester->profile->position->description }}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-9 text-center">
                            Head, Requesting Office/Authorized<br />Representative
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3">Date <div class="float-end">:</div></div>
                        <div class="col-9">
                            <div class="w-100 border-bottom border-dark text-light"> - </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 border-end border-dark p-0">
                    <div class="row mb-5">
                        <div class="col-2">
                            <div class="float-start border-bottom border-end border-dark pt-1 ps-1 pe-5 pb-2 fw-bold">B.</div>
                        </div>
                        <div class="col-10">
                            <div class="pt-3">
                                <div><strong>Certified:</strong> Budget available and utilized for</div>
                                <div>the purpose/adjustment necessary as</div>
                                <div>indicated above</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">Signature <div class="float-end">:</div></div>
                        <div class="col-9">
                            <div class="w-100 border-bottom border-dark text-light"> - </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">Printed Name <div class="float-end">:</div></div>
                        <div class="col-9">
                            <div class="w-100 border-bottom border-dark text-center">{{ $alobs->budget_office->profile->first_name }} {{ $alobs->budget_office->profile->last_name }}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">Position <div class="float-end">:</div></div>
                        <div class="col-9">
                            <div class="w-100 border-bottom border-dark text-center">{{ $alobs->budget_office->profile->position->description }}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-9 text-center">
                            Head, Budget Division/Unit/Authorized<br />Representative
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3">Date <div class="float-end">:</div></div>
                        <div class="col-9">
                            <div class="w-100 border-bottom border-dark text-light"> - </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row border-bottom border-start border-end border-dark py-1"></div>
            <div class="row border-bottom border-start border-end border-dark">
                <div class="col-1 border-end border-dark">
                    <div class="fw-bold">C.</div>
                </div>
                <div class="col-11 fw-bold text-center">
                    STATUS OF UTILIZATION
                </div>
            </div>
            <div class="row border-bottom border-start border-end border-dark">
                <div class="col-5 border-end border-dark fw-bold text-center">
                    Reference
                </div>
                <div class="col-7 fw-bold text-center">
                    Amount
                </div>
            </div>
            <div class="row border-bottom border-start border-end border-dark">
                <div class="col-1 border-end border-dark d-flex" style="align-items: center; justify-content: center;">
                    Date
                </div>
                <div class="col-2 border-end border-dark d-flex" style="align-items: center; justify-content: center;">
                    Particulars
                </div>
                <div class="col-2 border-end border-dark d-flex" style="align-items: center; justify-content: center;">
                    BURS/JEV/RCI/<br />RADAI/RTRAI No.
                </div>
                <div class="col-7 text-center p-0">
                    <table class="w-100 h-100">
                        <caption></caption>
                        <thead>
                            <tr>
                                <th class="border-end border-dark" rowspan="2" style="font-weight: normal !important; width: 20%;">
                                    <div class="mb-3">Utilization</div>
                                    <div>(a)</div>
                                </th>
                                <th class="border-end border-dark" rowspan="2" style="font-weight: normal !important; width: 20%;">
                                    <div class="mb-3">Payable</div>
                                    <div>(b)</div>
                                </th>
                                <th class="border-end border-dark" rowspan="2" style="font-weight: normal !important; width: 20%;">
                                    <div class="mb-3">Payment</div>
                                    <div>(c)</div>
                                </th>
                                <th class="border-bottom border-dark" colspan="2" style="font-weight: normal !important; width: 40%;">Balance</th>
                            </tr>
                            <tr>
                                <th class="border-end border-dark" style="font-weight: normal !important; width: 20%;">Not Yet Due</th>
                                <th class="border-dark" style="font-weight: normal !important; width: 20%;">Due and<br />Demandable</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="row border-bottom border-start border-end border-dark">
                <div class="col-1 border-end border-dark d-flex" style="align-items: center; justify-content: center;">
                    
                </div>
                <div class="col-2 border-end border-dark d-flex" style="align-items: center; justify-content: center;">
                    <div class="my-3 small">
                        "{{ $alobs->purchase_order->bac_reso->abstract_of_canvass->pr->purpose }}"
                    </div>
                </div>
                <div class="col-2 border-end border-dark d-flex" style="align-items: center; justify-content: center;">
                    
                </div>
                <div class="col-7 text-center p-0">
                    <table class="w-100 h-100 text-light">
                        <caption></caption>
                        <thead>
                            <tr>
                                <th class="border-end border-dark" rowspan="2" style="font-weight: normal !important; width: 20%;">
                                    -
                                </th>
                                <th class="border-end border-dark" rowspan="2" style="font-weight: normal !important; width: 20%;">
                                    -
                                </th>
                                <th class="border-end border-dark" rowspan="2" style="font-weight: normal !important; width: 20%;">
                                    -
                                </th>
                                <th class="border-end border-dark" style="font-weight: normal !important; width: 20%;">-</th>
                                <th class="border-dark" style="font-weight: normal !important; width: 20%;">-</th>
                            </tr>
                        </thead>
                    </table>
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