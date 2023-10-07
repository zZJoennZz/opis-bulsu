<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>
        {{ $parRecord->number }} - Property Acknowledgment Receipt
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
            font-family: 'Times New Roman', Times, serif;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="to-print">
        <div class="container-fluid">
            <div class="row mb-5">
                <div class="col-12 text-end fst-italic" style="font-size: 16px;">
                    Appendix 71
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <div class="text-center text-uppercase fw-bold" style="font-size: 16px;">
                        Property Acknowledgment Receipt
                    </div>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-8">
                    <div class="fw-bold">
                        Entity Name: <div class="d-inline border-bottom border-dark pe-5 text-uppercase">Bulacan State University</div>
                    </div>
                    <div>
                        <span class="fw-bold">
                            Fund Cluster: ________________________________________________
                        </span>
                    </div>
                </div>
                <div class="col-4 text-end">
                    <div class="text-light">
                        <span class="fw-bold">
                            ________________________________________________
                        </span>
                    </div>
                    <div class="fw-bold">
                        PAR No.: <div class="d-inline border-bottom border-dark pe-5">{{ $parRecord->number }}</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <table class="w-100">
                        <caption></caption>
                        <thead class="align-middle text-center">
                            <tr>
                                <th style="width: 10%; border: 1px solid #000;">Quantity</th>
                                <th style="width: 10%; border: 1px solid #000;">Unit</th>
                                <th style="width: 30%; border: 1px solid #000;">Description</th>
                                <th style="width: 15%; border: 1px solid #000;">Property No.</th>
                                <th style="width: 15%; border: 1px solid #000;">Date Acquired</th>
                                <th style="width: 20%; border: 1px solid #000;">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="text-center"
                            style="border-left: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">

                            @foreach ($parRecord->items as $item)
                            <tr style="border-bottom: none !important;">
                                <td style="border-bottom: 0px solid #fff !important;">{{ $item->quantity }}</td>
                                <td>{{ $item->bac_reso_item->quotation->pr_item->ppmp->item_detail->unit->uom }}</td>
                                <td>{{ $item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}, {{ $item->bac_reso_item->quotation->brand_and_model_offered }}</td>
                                <td>{{ $item->property_number }}</td>
                                <td>₱ {{ number_format($item->bac_reso_item->quotation->offered_unit_price, 2) }}</td>
                                <td>₱ {{ number_format($item->bac_reso_item->quotation->offered_unit_price * $item->quantity, 2) }}</td>
                            </tr>
                            @endforeach
                            @for ($i = 0; $i < 20 - count($parRecord->items); $i++)
                                <tr class="text-light">
                                    <td>-</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @endfor
                                <tr>
                                    <td colspan="6" class="fst-italic text-center">*** Nothing follows ***</td>
                                </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td style="border: 1px solid #000;" colspan="3">
                                    <div class="mb-5">Received from:</div>
                                    @foreach ($parRecord->issuers as $issuer)
                                    <div class="text-center mb-1">
                                        <div class="border-bottom border-dark w-75 m-auto">
                                            {{ $issuer->employee->first_name . ' ' . $issuer->employee->middle_name . ' ' .
                                            $issuer->employee->last_name }}
                                        </div>
                                        <div>Signature Over Printed Name</div>
                                    </div>
                                    <div class="text-center mb-1">
                                        <div class="border-bottom border-dark w-75 m-auto">
                                            {{ $issuer->employee->position->name }}
                                        </div>
                                        <div>Position/Office</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="border-bottom border-dark w-75 m-auto">
                                            {{ $parRecord->date_issued }}
                                        </div>
                                        <div>Date</div>
                                    </div>
                                    @endforeach
                                </td>
                                <td style="border: 1px solid #000;" colspan="3">
                                    <div class="mb-5">Received by:</div>
                                    <div class="text-center mb-1">
                                        <div class="border-bottom border-dark w-75 m-auto">
                                            @for ($i = 0; $i < count($parRecord->receivers); $i++)
                                                {{ $parRecord->receivers[$i]->end_user->first_name . ' ' .
                                                $parRecord->receivers[$i]->end_user->middle_name . ' ' .
                                                $parRecord->receivers[$i]->end_user->last_name }}

                                                @if ($i !== count($parRecord->receivers) - 1)
                                                /
                                                @endif
                                                @endfor
                                        </div>
                                        <div>Signature Over Printed Name</div>
                                    </div>
                                    <div class="text-center mb-1">
                                        <div class="border-bottom border-dark w-75 m-auto">
                                            @for ($i = 0; $i < count($parRecord->receivers); $i++)
                                                {{ $parRecord->receivers[$i]->end_user->position->name }}

                                                @if ($i !== count($parRecord->receivers) - 1)
                                                /
                                                @endif
                                                @endfor
                                        </div>
                                        <div>Position/Office</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="border-bottom border-dark w-75 m-auto">
                                            {{ $parRecord->date_issued }}
                                        </div>
                                        <div>Date</div>
                                    </div>
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