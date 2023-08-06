<x-dashboard-layout>
    <x-slot:title>
        All Property Transfers
        </x-slot>

        @php
        $breadcrumb = [
        ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
        ['name' => 'All Property Transfers'],
        ]
        @endphp

        <x-breadcrumb :breadcrumb="$breadcrumb" />
        <div class="table-responsive">
            <table class="table table-sm table-hover border-dark caption-top" id="all-property-transfers">
                <caption>All Property Transfers</caption>
                <thead style="font-size: 11px;">
                    <tr>
                        <th style="width: 30%;">Item Detail</th>
                        <th style="width: 10%;">Transfer Date</th>
                        <th style="width: 20%;">Issued By</th>
                        <th style="width: 20%;">Received By</th>
                        <th style="width: 20%;">College</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allTransfers as $transfer)
                    <tr>
                        <td>
                            <div>
                                <div>
                                    <span class="badge bg-secondary">{{ $transfer->number }}</span>
                                    <a href="{{ route('transfer.print', ['transferId' => $transfer->id]) }}" target="_blank"
                                        class="btn btn-sm btn-link float-end">Print</a>
                                </div>
                                <div class="mb-2">
                                    <strong>{{ $transfer->item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}</strong> / {{
                                    $transfer->quantity
                                    }} {{ $transfer->item->bac_reso_item->quotation->pr_item->ppmp->item_detail->unit->uom }}
                                </div>
                            </div>
                        </td>
                        <td>{{ date('Y-m-d', strtotime($transfer->created_at)) }}</td>
                        <td>
                            @foreach ($transfer->issuers as $issuer)
                            <div>{{ $issuer->end_user->first_name . ' ' . $issuer->end_user->middle_name . ' ' . $issuer->end_user->last_name }}</div>
                            <div class="fst-italic text-secondary" style="font-size: 11px;">{{ $issuer->end_user->position->name }}</div>
                            @endforeach
                        </td>
                        <td>
                            @foreach ($transfer->receivers as $receiver)
                            <div>{{ $receiver->end_user->first_name . ' ' . $receiver->end_user->middle_name . ' ' . $receiver->end_user->last_name }}
                            </div>
                            <div class="fst-italic text-secondary" style="font-size: 11px;">{{ $receiver->end_user->position->name }}</div>
                            @endforeach
                        </td>
                        <td>
                            {{ $transfer->item->transaction->branch->branch_name }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <x-slot:additional_script>
            @include('layout/datatable', ['tableId' => 'all-property-transfers'])
            </x-slot>
</x-dashboard-layout>