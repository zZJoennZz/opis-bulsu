<x-dashboard-layout>
    <x-slot:title>
        View Purchase Order
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Purchase Order', 'route' => 'po.all'],
            ['name' => 'New Purchase Order'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    <div class="float-end">
        <button class="btn btn-sm btn-secondary" onclick="Popup()"><em class="bi bi-printer-fill"></em> Print</button>
    </div>
    <div id="print-area" class="print-area container">
        <div class="small mb-3">
            <div class="mb-2">Standard Form Number: SF-GOOD-58</div>
            <div class="mb-2">Revised on: May 24, 2004</div>
            <div>Standard Form Title: Purchase Order</div>
        </div>
        <div class="text-center">
            <div class="text-uppercase fs-4 mb-1">
                Purchase Order
            </div>
            <div class="text-uppercase fs-4 mb-2">
                Bulacan State University
            </div>
        </div>
        <div class="row">
            <div class="col-6 border-end border-dark border-top border-start border-end">
                <div>Supplier: {{ $po->canvass_abstract->company->name }}</div>
                <div>Address: {{ $po->canvass_abstract->company->full_address }}</div>
                <div>Email Address: {{ $po->canvass_abstract->company->email_address }}</div>
                <div>Tel/Cel No.: {{ $po->canvass_abstract->company->contact_number }}</div>
                <div>TIN: {{ $po->canvass_abstract->company->tin }}</div>
            </div>
            <div class="col-6 d-flex justify-content-center flex-column border-dark border-top border-end">
                <div>PO No.: {{ $po->po_number }}</div>
                <div>PO No.: {{ date_format($po->created_at, 'Y/m/d h:i:s A') }}</div>
                <div>Mode of Payment: {{ $po->mop->name }}</div>
            </div>
            <div class="border-top border-dark small p-3 border-dark border-start border-end">
                <div>Gentlemen:</div>
                <div>
                    Please furnish this office the following articles subject to the terms and conditions herein:
                </div>
            </div>
            <div class="container">
                <div class="border-top border-start border-end border-dark row">
                    <div class="col-6 border-end border-dark">
                        <div class="mb-2 p-2">
                            Place of Delivery: ______________________________________________________________
                        </div>
                        <div class="p-2">
                            Date of Delivery: ______________________________________________________________
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-2 p-2">
                            Delivery Term: ______________________________________________________________
                        </div>
                        <div class="p-2">
                            Payment Term: ______________________________________________________________
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered border-dark table-sm small mb-0">
                <thead>
                    <tr>
                        <th style="width: 15%">Stock/Property No.</th>
                        <th style="width: 10%">Unit</th>
                        <th style="width: 35%">Description</th>
                        <th style="width: 10%">Quantity</th>
                        <th style="width: 15%">Unit Cost</th>
                        <th style="width: 15%">Total Cost</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalAmount = 0;
                    @endphp
                    @foreach ($po->canvass_abstract->items as $item)
                        @php
                            $totalQty = 0;

                            foreach ($item->quotation_item->pr_item->ppmp->milestones as $milestone) {
                                $totalQty += $milestone->milestone_value;
                            }
                        @endphp
                        <tr>
                            <td>{{ $item->quotation_item->item_number }}</td>
                            <td>{{ $item->quotation_item->pr_item->ppmp->item_detail->unit->uom }}</td>
                            <td>{{ $item->quotation_item->pr_item->ppmp->item_detail->description }}</td>
                            <td>{{ $totalQty }}</td>
                            <td>₱ <span class="float-end">{{ number_format($item->quotation_item->offered_unit_price, 2) }}</span></td>
                            <td>₱ <span class="float-end">{{ number_format($item->quotation_item->offered_unit_price * $totalQty, 2) }}</span></td>
                            @php
                                $totalAmount += $item->quotation_item->offered_unit_price * $totalQty;
                            @endphp
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-end text-uppercase">Total:</td>
                        <td>₱ <span class="float-end">{{ number_format($totalAmount, 2) }}</span></td>
                    </tr>
                </tfoot>
            </table>
            <div class="border-dark border-bottom border-start border-end fw-bold text-uppercase">
                {{ translateToWords($totalAmount) }}
            </div>
            <div class="border-dark border-bottom border-start border-end small">
                In case of failure to make the full delivery within the time specified above, a penalty of one tenth(1/10) of one percent for every day of delivery shall be imposed on the undelivered items/s.
            </div>
            <div class="col-6 border-dark border-bottom border-start border-end small d-flex flex-column align-items-center justify-content-center py-5">
                <div class="mb-5">Conforme:</div>
                <div class="mb-2">_________________________</div>
                <div class="mb-5">Signature over Printed Name of Supplier</div>
                <div class="mb-2">_________________________</div>
                <div>Date</div>
            </div>
            <div class="col-6 border-dark border-bottom border-end small d-flex flex-column align-items-center justify-content-center text-center">
                <div class="mb-5">Very truly yours,</div>
                <div class="mb-2">_________________________</div>
                <div class="mb-2">Signature over Printed Name of Authorized Official</div>
                <div class="mb-2">President</div>
            </div>
            <div class="col-6 border-dark border-bottom border-start border-end small py-2">
                <div>Fund Cluster: ____________________</div>
                <div class="mb-3">Funds Available: ____________________</div>
                <div class="text-center mb-2">
                    Signature over Printed Name of Supplier
                </div>
                <div class="text-center">
                    Accounting Division Unit
                </div>
            </div>
            <div class="col-6 border-dark border-bottom border-end small d-flex flex-column justify-content-center">
                <div class="mb-3">ORS/BURS No.: ____________________</div>
                <div class="mb-3">Date of ORS/BURS: ____________________</div>
                <div>Amount: ____________________</div>
            </div>
        </div>
    </div>

    <x-slot:additional_script>
        <script>
            function Popup() {
                let data = $('#print-area').html();
                var mywindow = window.open('', 'new div', 'height=400,width=600');
                mywindow.document.write('<html><head><title></title>');
                mywindow.document.write('<link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" type="text/css" />');
                mywindow.document.write('<style>@media print { html,body {width:297mm;height:210mm} }</style>');
                mywindow.document.write('</head><body >');
                mywindow.document.write(data);
                mywindow.document.write('</body></html>');
                mywindow.document.close();
                mywindow.focus();
                setTimeout(function(){mywindow.print();},1000);

                return true;
            }
        </script>
    </x-slot>
</x-dashboard-layout>