<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h2 class="h3">Supply Office Dashboard <span class="badge bg-primary">{{ Auth::user()->ppmp_year }}</span></h2>
</div>
<div class="row">
    <div class="col-12">
        <table class="table table-sm caption-top" id="ics-table">
            <caption class="text-uppercase fw-bold bg-primary ps-2 text-light">Inventory Custodian Report</caption>
            <thead>
                <tr>
                    <th style="width: 10%;">ICS No.</th>
                    <th style="width: 30%;">Item</th>
                    <th style="width: 20%;">Quantity/Unit</th>
                    <th style="width: 10%;">Unit Cost</th>
                    <th style="width: 10%;">Total Cost</th>
                    <th style="width: 15%;">Fund Cluster Code</th>
                    <th style="width: 5%;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ics as $i)
                    @php
                        $totalQty = 0;
                        foreach ($i->bac_reso->quotation->pr_item->ppmp->milestones as $m) {
                            $totalQty += $m->milestone_value;
                        }
                    @endphp
                    <tr>
                        <td>{{ $i->ics_number }}</td>
                        <td>{{ $i->bac_reso->quotation->pr_item->ppmp->item_detail->description }}</td>
                        <td>{{ $totalQty }} {{ $i->bac_reso->quotation->pr_item->ppmp->item_detail->unit->uom }}</td>
                        <td>₱ {{ number_format($i->bac_reso->quotation->offered_unit_price, 2) }}</td>
                        <td>₱ {{ number_format($i->bac_reso->quotation->offered_unit_price * $totalQty, 2) }}</td>
                        <td>{{ $i->source_of_fund->source_of_fund }}</td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                                <a class="btn btn-outline-primary"><em class="bi bi-pencil-square"></em></a>
                                <button type="submit" class="btn btn-outline-primary"><em class="bi bi-trash-fill"></em></button>
                                <a href="{{ route('icf.print', ['id' => $i->id]) }}" target="_blank" class="btn btn-outline-primary"><em class="bi bi-printer-fill"></em></a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@include('layout/datatable', ["tableId" => "ics-table"])