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
        <button class="btn btn-sm btn-secondary" onclick="window.print()"><em class="bi bi-printer-fill"></em> Print</button>
    </div>
    <div class="for-print p-0" style="font-family:Arial, Helvetica, sans-serif;">
        <div class="small mb-3">
            <div class="fw-bold">BULACAN STATE UNIVERSITY</div>
            <div>Standard Form Number: SF-GOOD-58</div>
            <div>Revised on: May 24, 2004</div>
            <div>Standard Form Title: Purchase Order</div>
        </div>
        <div class="text-center">
            <div class="text-uppercase fs-4 fw-bold">
                Purchase Order
            </div>
            <div class="text-uppercase fs-4 mb-2 fw-bold" style="font-family: 'Arial Narrow'">
                Bulacan State University
            </div>
        </div>
        <div class="row">
            <div class="col-8 border-end border-dark border-top border-start border-end small">
                <div><strong>Supplier:</strong> <strong>{{ $po->company->name }}</strong></div>
                <div><strong>Address:</strong> {{ $po->company->full_address }}</div>
                <div><strong>Email Address:</strong> {{ $po->company->email_address }}</div>
                <div><strong>Tel/Cel No.:</strong> {{ $po->company->contact_number }}</div>
                <div><strong>TIN:</strong> {{ $po->company->tin }}</div>
            </div>
            <div class="col-4 d-flex justify-content-start small fw-bold flex-column border-dark border-top border-end">
                <div><small>PO No.:</small> {{ $po->po_number }}</div>
                <div><small>Date:</small> {{ date_format($po->created_at, 'Y/m/d') }}</div>
                <div><small>Mode of Procurement:</small> {{ $po->mode_of_procurement->name }}</div>
            </div>
            <div class="border-top border-dark small p-1 border-dark border-start border-end">
                <div class="ps-3">Gentlemen:</div>
                <div class="fw-bold text-center">
                    Please furnish this office the following articles subject to the terms and conditions herein:
                </div>
            </div>
            <div class="col-12 border-dark border-top border-start border-end">
                <div class="row">
                    <div class="col-8 small">
                        <div class="row">
                            <div class="col-3 border-end border-dark">
                                <div><strong>Place of delivery:</strong></div>
                                <div><strong>Date of Delivery:</strong></div>
                            </div>
                            <div class="col-9 p-0 border-end border-dark fw-bold">
                                <div class="border-bottom border-dark">
                                    {{ $po->place_of_delivery }}
                                </div>
                                <div class="border-bottom border-dark">
                                    {{ $po->date_of_delivery }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 small border-bottom border-dark fw-bold">
                        <div>Delivery Term: {{ $po->delivery_term }}</div>
                        <div>Payment Term: {{ $po->mop->name }}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 small">
                        <div class="row">
                            <div class="col-2 border-end border-dark">
                                <strong>For Inquiry:</strong>
                            </div>
                            <div class="col-10 p-0 fw-bold">
                                {{ $po->for_inquiry }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered border-dark table-sm small mb-0">
                <thead class="text-center small align-middle">
                    <tr>
                        <th style="width: 7%">Stock/<br />Property No.</th>
                        <th style="width: 7%">Unit</th>
                        <th style="width: 45%">Description</th>
                        <th style="width: 10%">Quantity</th>
                        <th style="width: 10%">Unit Cost</th>
                        <th style="width: 10%">Total Cost</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="text-danger">Purpose: {{ $po->bac_reso->abstract_of_canvass->pr->purpose }}</td>
                        <td></td>
                        <td></td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>-</td>
                    </tr>
                    @php
                        $totalAmount = 0;
                        $ctr = 1;
                    @endphp
                    @foreach ($po->bac_reso->bac_reso_items as $item)
                        @if ($item->quotation->quotation !== null)
                            @php
                                $totalQty = 0;
                                
                                foreach ($item->quotation->pr_item->ppmp->milestones as $milestone) {
                                    $totalQty += $milestone->milestone_value;
                                }
                            @endphp
                            <tr>
                                <td class="text-center">{{ $ctr }}</td>
                                <td class="text-center">{{ $item->quotation->pr_item->ppmp->item_detail->unit->uom }}</td>
                                <td>{{ $item->quotation->pr_item->ppmp->item_detail->description }}</td>
                                <td class="text-center">{{ $totalQty }}</td>
                                <td>₱ <span class="float-end">{{ number_format($item->quotation->offered_unit_price, 2) }}</span></td>
                                <td>₱ <span class="float-end">{{ number_format($item->quotation->offered_unit_price * $totalQty, 2) }}</span></td>
                                @php
                                    $totalAmount += $item->quotation->offered_unit_price * $totalQty;
                                @endphp
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>{{ $item->quotation->pr_item->ppmp->item_detail->article }}</td>
                                <td></td>
                                <td></td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>{{ $item->quotation->pr_item->ppmp->item_detail->extra_article }}</td>
                                <td></td>
                                <td></td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>-</td>
                            </tr>
                            @php
                                $ctr += 1;
                            @endphp
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5">
                            <div class="float-start" style="font-size: 11px;">
                                (Total Amount in Words)
                            </div>
                            <div class="text-center fst-italic">
                                {{ translateToWords($totalAmount) }}
                            </div>
                        </td>
                        <td class="fw-bold">₱ <span class="float-end">{{ number_format($totalAmount, 2) }}</span></td>
                    </tr>
                </tfoot>
            </table>
            <div class="border-dark border-start border-end small">
                <div class="small">
                    In case of failure to make the full delivery within the time specified above, a penalty of <strong>one tenth (1/10)</strong> of one percent for every day of delay shall be imposed on the undelivered item/s.
                </div>
                <div class="row">
                    <div class="col-4"></div>
                    <div class="col-4">
                        <div>Conforme:</div>
                        <div class="border-bottom border-dark fw-bold text-center">{{ $po->company->name }}</div>
                        <div class="text-center" style="font-size: 11px;">Signature over Printed Name of Supplier</div>
                        <div class="mx-auto border-bottom border-dark w-50 text-center">{{ date_format($po->created_at, 'Y/m/d') }}</div>
                        <div class="text-center" style="font-size: 11px;">Date</div>
                    </div>
                    <div class="col-4">
                        <div>Very truly yours,</div>
                        <div style="color: #fff;">-</div>
                        <div class="border-bottom border-dark fw-bold text-center">{{ getSettingValue('university_president') }}</div>
                        <div class="text-center">President</div>
                    </div>
                </div>
            </div>
            <div class="col-8 border-end border-dark border-top border-start border-end small border-bottom">
                <div class="row p-0">
                    <div class="col-3 fw-bold" style="font-size: 11px;">
                        <div>Fund Cluster:</div>
                        <div>Funds Available:</div>
                    </div>
                    <div class="col-9">
                        <div class="border-bottom border-dark">
                            <div style="font-size: 11px;">
                                {{ $po->bac_reso->bac_reso_items[0]->quotation->pr_item->ppmp->source_of_fund->source_of_fund }}
                            </div>
                        </div>
                        <div class="border-bottom border-dark mb-4">
                            <div style="font-size: 11px; color: #fff;">
                                -
                            </div>
                        </div>
                        <div class="border-bottom border-dark text-center fw-bold">
                            {{ getSettingValue('chief_accountant') }}
                        </div>
                        <div class="text-center">Chief Accountant</div>
                    </div>
                </div>
            </div>
            <div class="col-4 d-flex justify-content-start small fw-bold flex-column border-dark border-top border-end border-bottom">
                <div><small>ORS/BURS No.:</small> ____________________</div>
                <div><small>Date of the ORS/BURS:</small> _______________</div>
                <div><small>Amount:</small> __________________________</div>
            </div>
            <div class="col-12">
                <div class="row p-0" style="font-size: 11px;">
                    <div class="col-1 text-center">Reso#</div>
                    <div class="col-1 text-center">{{ $po->bac_reso->b_a_c_reso_number }}</div>
                    <div class="col-10">
                        PR#: {{ $po->bac_reso->abstract_of_canvass->pr->pr_number }} {{ $po->bac_reso->bac_reso_items[0]->quotation->pr_item->ppmp->source_of_fund->source_of_fund }}
                    </div>
                </div>
            </div>
            <div class="col-12 p-0">
                <div class="text-end" style="font-size: 11px; p-0">
                    Pls. Call Supply Office Two (2) Days Before the Delivery Date
                </div>
            </div>
            {{-- <div class="col-6 border-dark border-bottom border-start border-end small d-flex flex-column align-items-center justify-content-center py-5">
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
            </div> --}}
        </div>
    </div>

    <x-slot:additional_script>
        
    </x-slot>
</x-dashboard-layout>