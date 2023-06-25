<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <h2 class="fw-bold text-uppercase fs-5">Supply Office Dashboard</h2>
</div>
<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-sm table-hover border-dark caption-top" id="ics-items">
                <caption class="fw-bold bg-primary text-light ps-1 rounded-top">ICS Items</caption>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Total Cost</th>
                        <th>Fund Cluster</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ics_inventory as $i)
                    @foreach ($i->items as $item)
                    <tr>
                        <td>
                            <div class="fw-bold mb-1">
                                {{ $item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}
                                @if ($i->type === "ICSL")
                                <span class="badge bg-secondary">Low Value</span>
                                @else
                                <span class="badge bg-primary">High Value</span>
                                @endif
                            </div>
                            <div class="text-secondary small"><strong>ICS No.:</strong> {{ $i->number }}</div>
                            <div class="text-secondary small"><strong>Date Acquired:</strong> {{ date('Y-m-d', strtotime($i->date_acquired)) }}</div>
                            <div class="text-secondary small"><strong>Issued By:</strong>
                                @foreach ($i->issuers as $issuer)
                                {{ $issuer->employee->first_name }} {{ $issuer->employee->middle_name }} {{ $issuer->employee->last_name }}
                                / <span class="fst-italic">{{ $issuer->employee->position->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td>₱ {{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                        <td>{{ $item->bac_reso_item->quotation->pr_item->ppmp->source_of_fund->description }}</td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
        <hr />
        <div class="table-responsive">
            <table class="table table-sm table-hover border-dark caption-top" id="par-items">
                <caption class="fw-bold bg-primary text-light ps-1 rounded-top">PAR Items</caption>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Total Cost</th>
                        <th>Fund Cluster</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($par_inventory as $i)
                    @foreach ($i->items as $item)
                    <tr>
                        <td>
                            <div class="fw-bold mb-1">{{ $item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}</div>
                            <div class="text-secondary small"><strong>PAR No.:</strong> {{ $i->number }}</div>
                            <div class="text-secondary small"><strong>Date Acquired:</strong> {{ date('Y-m-d', strtotime($i->date_acquired)) }}</div>
                            <div class="text-secondary small"><strong>Issued By:</strong>
                                @foreach ($i->issuers as $issuer)
                                {{ $issuer->employee->first_name }} {{ $issuer->employee->middle_name }} {{ $issuer->employee->last_name }}
                                / <span class="fst-italic">{{ $issuer->employee->position->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td>₱ {{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                        <td>{{ $item->bac_reso_item->quotation->pr_item->ppmp->source_of_fund->description }}</td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@include('layout/datatable', ['tableId' => 'par-items'])
@include('layout/datatable', ['tableId' => 'ics-items'])