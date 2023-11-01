@php
    $content = json_decode($snapShot->content, false, 512, JSON_UNESCAPED_SLASHES);
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>
            {{ $content->end_user->first_name . ' ' . $content->end_user->middle_name . ' ' . $content->end_user->last_name }} as of {{ $snapShot->created_at }} Inventory Custodian Receipt (General Inventory) |  OPIS - BulSU e-PROCUREMENT
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
                    margin: 0;
                    page-break-after: always;
                }
            }
        </style>
    </head>
    <body>
        <div class="to-print">
            <div class="container-fluid">
                <div class="row mb-4">
                    <div class="col-7 m-auto">
                        <img src="{{ asset('img/bsu-small-logo.png') }}" alt="BSU Logo" style="position: absolute; top: 0; left: 20vw; width: 75px;" />
                        <div class="text-center">
                            <div>Republic of the Philippines</div>
                            <div class="fw-bold">BULACAN STATE UNIVERSITY</div>
                            <div class="fst-italic">City of Malolos, Bulacan</div>
                            <div class="fw-bold text-uppercase mt-5">Inventory Custodian Receipt</div>
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-12">
                        <table class="table table-bordered border-dark table-sm">
                            <thead class="align-middle text-center">
                                <tr>
                                    <th>Qty</th>
                                    <th>Unit</th>
                                    <th>Description</th>
                                    <th>Date Acquired</th>
                                    <th>Unit Value</th>
                                    <th>Total Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($content->items as $item)
                                <tr>
                                    @php
                                        $itemQty = count($item->properties);
                                    @endphp
                                    <td class="text-center">{{ $itemQty }}</td>
                                    <td class="text-center">{{ $item->bac_reso_item->quotation->pr_item->ppmp->item_detail->unit->uom }}</td>
                                    <td>{{ $item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}, {{ $item->bac_reso_item->quotation->brand_and_model_offered }}</td>
                                    <td class="text-center">{{ $item->transaction->date_acquired }}</td>
                                    <td class="text-end">{{ number_format($item->bac_reso_item->quotation->offered_unit_price, 2) }}</td>
                                    <td class="text-end">{{ number_format($item->bac_reso_item->quotation->offered_unit_price * $itemQty, 2) }}</td>
                                </tr>
                                @endforeach
                                @for ($i = 0; $i < 9 - count($content->items); $i++)
                                <tr>
                                    <td class="text-light">-</td>
                                    <td class="text-light"></td>
                                    <td class="text-light"></td>
                                    <td class="text-light"></td>
                                    <td class="text-light"></td>
                                    <td class="text-light"></td>
                                </tr>
                                @endfor
                                <tr>
                                    <td colspan="6" class="text-center">*** Nothing follows ***</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-5">
                        <div class="mb-3">Acknowledge by:</div>
                        <div>{{ $content->end_user->first_name . ' ' . $content->end_user->middle_name . ' ' . $content->end_user->last_name }}</div>
                        <div class="mb-3">{{ $content->end_user->position->name }} : {{ $content->end_user->branch->branch_name }}</div>
                        <div>Date: _______________</div>
                    </div>
                    <div class="col-3">

                    </div>
                    <div class="col-4">
                        <div class="mb-3">Inspected by:</div>
                        <div>Inventory Committee Team</div>
                        <div class="mb-3 text-light">-</div>
                        <div>Date: _______________</div>
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