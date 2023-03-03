<x-dashboard-layout>
    <x-slot:title>
        View Inspection and Acceptance
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Inspection and Acceptance Report', 'route' => 'ia.all'],
            ['name' => 'View'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />

    <div class="container">
        <div class="row mb-3">
            <div class="col-12">
                <div class="fst-italic">
                    Appendix 62
                </div>
                <div class="fs-3 text-uppercase text-center mb-4">
                    Inspection and Acceptance Report
                </div>
                <div class="row">
                    <div class="col-6">
                        Entity Name: _____________________________
                    </div>
                    <div class="col-6">
                        Fund Cluster: _____________________________
                    </div>
                </div>
            </div>
        </div>
        <div class="row border-start border-end border-top border-bottom border-dark">
            <div class="col-6 border-end border-dark">Supplier: {{ $iaa[0]->purchase_order->canvass_abstract->company->name }}</div>
            <div class="col-6">IAR No.: {{ $iaa[0]->iar_no }}</div>
        </div>
        <div class="row border-start border-end border-bottom border-dark">
            <div class="col-6 border-end border-dark">PO No./Date: ({{ $iaa[0]->purchase_order->po_number }} / {{ date_format($iaa[0]->purchase_order->created_at, 'm/d/Y h:i:s A') }})</div>
            <div class="col-6">Date: {{ $iaa[0]->iar_date }}</div>
        </div>
        <div class="row border-start border-end border-bottom border-dark">
            <div class="col-6 border-end border-dark">Requisitioning Office/Dept.: {{ $iaa[0]->branch->branch_name }}</div>
            <div class="col-6">Invoice No.:</div>
        </div>
        <div class="row border-start border-end border-dark">
            <div class="col-6 border-end border-dark">Responsibility Center Code: {{ $iaa[0]->responsibility_center_code }}</div>
            <div class="col-6"></div>
        </div>
        <div class="row">
            <table class="table table-sm table-bordered border-dark small mb-0">
                <thead>
                    <tr style="background-color: #dbdbdb;" class="fst-italic text-center">
                        <th style="width: 20%;">Stock / Property No.</th>
                        <th style="width: 40%;">Description</th>
                        <th style="width: 20%;">Unit</th>
                        <th style="width: 20%;">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($iaa[0]->purchase_order->canvass_abstract->items as $item)
                        @php
                            $totalQty = 0;
                            foreach($item->quotation_item->pr_item->ppmp->milestones as $m) {
                                $totalQty += $m->milestone_value;
                            }
                        @endphp
                        <tr class="text-center">
                            <td>{{$item->quotation_item->item_number}}</td>
                            <td>{{$item->quotation_item->pr_item->ppmp->item_detail->description}}</td>
                            <td>{{$item->quotation_item->pr_item->ppmp->item_detail->unit->uom}}</td>
                            <td>{{$totalQty}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="background-color: #dbdbdb;" class="row border-start border-end border-bottom border-dark">
            <div class="col-6 border-end border-dark text-uppercase text-center">Inspection</div>
            <div class="col-6 text-uppercase text-center">Acceptance</div>
        </div>
        <div class="row border-start border-end border-bottom border-dark">
            <div class="col-6 border-end border-dark py-3">
                <div>Date Inspected: _______________________</div>
                <div class="ps-5"><input type="checkbox" class="me-2">Inspected, verified and found in order as to quantify and inspections.</div>
            </div>
            <div class="col-6 py-3">
                <div>Date Received: _______________________</div>
                <div class="ps-5"><input type="checkbox" class="me-2">Complete</div>
                <div class="ps-5"><input type="checkbox" class="me-2">Partial</div>
            </div>
        </div>
        <div class="row border-start border-end border-bottom border-dark">
            <div class="col-6 border-end border-dark py-3 text-center">
                <div>________________________</div>
                <div>Inspection Officer/Inspection Committee</div>
            </div>
            <div class="col-6 py-3 text-center">
                <div>________________________</div>
                <div>Supply and/or Property Custodian</div>
            </div>
        </div>
    </div>

    <x-slot:additional_script>
        
    </x-slot>
</x-dashboard-layout>