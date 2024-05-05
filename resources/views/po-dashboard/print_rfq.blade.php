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
                font-size: 11px;
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
                <div class="col-6">
                    1. Valid and Current Mayor's / Business Permit
                </div>
                <div class="col-6">
                    4. Omnibus Sworn Statement <span class="text-danger">(for ABC's above Php 50,000.00)</span>
                </div>
                <div class="col-6">
                    2. Valid and Current PhilGEPS Registration Certificate / Number
                </div>
                <div class="col-6">
                    5. Philippine Contractors Accreditation Board License <span class="text-danger">(Infrastructure)</span>
                </div>
                <div class="col-6">
                    3. Income / Business Tax Return <span class="text-danger">(for ABC's Above Php 500,000.00)</span>
                </div>
                <div class="col-6">
                    6. Professional License / Curriculum Vitae <span class="text-danger">(Consulting Services)</span>
                </div>
                <div class="col-12 fw-bold">
                    III. TO BE FILLED BY PROCUREMENT PERSONNEL
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-6">
                            <div class="row" style="font-size: 10px;">
                                <div class="col-5">DATE CREATED<div class="float-end">:</div></div>
                                <div class="col-7 border-bottom border-dark">{{ date("Y-m-d", strtotime($rfq->created_at)) }}</div>
                            </div>
                            <div class="row" style="font-size: 10px;">
                                <div class="col-5">DEADLINE OF SUBMISSION<div class="float-end">:</div></div>
                                <div class="col-7 border-bottom border-dark">{{ date("Y-m-d", strtotime($rfq->deadline_of_submission)) }}</div>
                            </div>
                            <div class="row" style="font-size: 10px;">
                                <div class="col-5">MODE OF PROCUREMENT<div class="float-end">:</div></div>
                                <div class="col-7 border-bottom border-dark">{{ $rfq->mode_of_procurement->name }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row" style="font-size: 10px;">
                                <div class="col-5">ENDUSER<div class="float-end">:</div></div>
                                <div class="col-7 border-bottom border-dark">{{ $rfq->purchase_request->requester->profile->first_name }} {{ $rfq->purchase_request->requester->profile->last_name }}</div>
                            </div>
                            <div class="row" style="font-size: 10px;">
                                <div class="col-5">PR. NO.<div class="float-end">:</div></div>
                                <div class="col-7 border-bottom border-dark">{{ $rfq->purchase_request->pr_number }}</div>
                            </div>
                            <div class="row" style="font-size: 10px;">
                                <div class="col-12">APPROVED BUDGET FOR THE CONTRACT:</div>
                                <div class="col-2"></div>
                                <div class="col-10 border-bottom border-dark">Php {{ $rfq->approved_budget }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    PURPOSE:
                </div>
                <div class="col-12">
                    {{ $rfq->purchase_request->purpose }}
                </div>
            </div>
            <div class="row p-0">
                <div class="col-12 p-0">
                    <table class="table table-bordered border-dark w-100 m-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%;">NO.<div class="mt-2">(a)</div></th>
                                <th class="text-center" style="width: 5%;">QTY.<div class="mt-2">(b)</div></th>
                                <th class="text-center" style="width: 6%;">UNIT<div class="mt-2">(c)</div></th>
                                <th class="text-center" style="width: 20%;">GENERAL NAME OF THE ITEM<div class="mt-2">(d)</div></th>
                                <th class="text-center" style="width: 30%;">REQUIRED ITEM SPECIFICATION<div class="mt-2">(e)</div></th>
                                <th class="text-center" style="width: 7%;">UNIT COST<div class="mt-2">(f)</div></th>
                                <th class="text-center" style="width: 20%;">BIDDER OFFERED SPECIFICATION AND BRAND<div class="mt-2">(g)</div></th>
                                <th class="text-center" style="width: 7%;">QUOTED UNIT PRICE<div class="mt-2">(h)</div></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rfq->purchase_request->pr_items as $item)
                            <tr>
                                <td>{{ $item->item_number }}</td>
                                <td>
                                    @php
                                        $qty = 0;
                                    @endphp
                                    @foreach ($item->ppmp->milestones as $m)
                                        @php
                                            $qty += $m->milestone_value;
                                        @endphp
                                    @endforeach

                                    {{ $qty }}

                                    @php
                                        $qty = 0;
                                    @endphp
                                </td>
                                <td>{{ $item->ppmp->item_detail->unit->uom }}</td>
                                <td>{{ $item->ppmp->item_detail->description }}</td>
                                <td>{{ $item->ppmp->item_detail->article }}, {{ $item->ppmp->item_detail->extra_article }}</td>
                                <td></th>
                                <td></td>
                                <td></th>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row border-bottom border-start border-end border-dark">
                <div class="col-7 border-end border-dark">
                    <div class="row">
                        <div class="col-12">
                            <div class="text-uppercase fw-bold">IV. MANDATORY TO BE FILLED OUT BY BIDDER</div>
                        </div>
                        <div class="col-4">
                            <div class="text-uppercase">1. Company name / Bank Name*</div>
                        </div>
                        <div class="col-8">
                            <div class="text-uppercase border-bottom border-dark">:</div>
                        </div>
                        <div class="col-4">
                            <div class="text-uppercase">2. Bank Account No.*</div>
                        </div>
                        <div class="col-8">
                            <div class="text-uppercase border-bottom border-dark">:</div>
                        </div>
                        <div class="col-4">
                            <div class="text-uppercase">3. Address*</div>
                        </div>
                        <div class="col-8">
                            <div class="text-uppercase border-bottom border-dark">:</div>
                        </div>
                        <div class="col-4">
                            <div class="text-uppercase">4. TAX ID No.*</div>
                        </div>
                        <div class="col-8">
                            <div class="text-uppercase border-bottom border-dark">:</div>
                        </div>
                        <div class="col-4">
                            <div class="text-uppercase">5. Contact No.*</div>
                        </div>
                        <div class="col-8">
                            <div class="text-uppercase border-bottom border-dark">:</div>
                        </div>
                        <div class="col-4">
                            <div class="text-uppercase">6. Email Address*</div>
                        </div>
                        <div class="col-8">
                            <div class="text-uppercase border-bottom border-dark">:</div>
                        </div>
                        <div class="col-4">
                            <div class="text-uppercase">7. DELIVERY PERIOD*</div>
                        </div>
                        <div class="col-8">
                            <div class="text-uppercase border-bottom border-dark">:</div>
                        </div>
                        <div class="col-12 mt-4">
                            <div class="fw-bold">Bidders Declaration:</div>
                            <div class="text-center w-75 m-auto">
                                We have <span class="fw-bold">"Read and Agreed with the Instructions and Terms and Conditions"</span> stated in the quotation and <span class="fw-bold">"Reviewed and Complied"</span> with detailed specifications signed by duly authorized representative of our company.
                            </div>
                        </div>
                        <div class="col-2">

                        </div>
                        <div class="col-8 text-center">
                            <div class="w-100 mt-5 border-top border-dark">
                                Bidder's Authorized Representative
                                <div style="font-size: 10px;">(Printed Name and Signature)</div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="w-100 mt-5 text-center border-top border-dark">
                                Date
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="fw-bold">BulSU-OP-PU-03F3</div>
                            <div class="fw-bold">Revision 2</div>
                        </div>
                    </div>
                </div>
                <div class="col-5">
                    <div class="row border-bottom border-dark">
                        <div class="col-12">
                            <div class="text-uppercase fw-bold">V. MANDATORY TO BE FILLED OUT BY BUYER/PERSONNEL</div>
                        </div>
                        <div class="col-6">
                            <div class="text-uppercase">Date of Canvass</div>
                        </div>
                        <div class="col-6">
                            <div class="text-uppercase border-bottom border-dark">:</div>
                        </div>
                        <div class="col-12">
                            <div style="font-size: 10px;">Canvassed by:</div>
                            <div class="m-auto w-75 mt-4 border-top border-dark text-center">Buyer's Name and Signature</div>
                            <div class="m-auto w-75 mt-5 border-top border-dark text-center">Buyer's Name and Signature</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="text-uppercase">BY THE AUTHORITY OF THE PROCUREMENT OFFICE</div>
                        </div>

                        <div class="col-12">
                            <div class="mt-5 text-center">
                                {{ getSettingValue("head_asset_management_unit") }}
                            </div>
                            <div class="m-auto w-75 border-top border-dark text-center">Head of Procurement Office</div>
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