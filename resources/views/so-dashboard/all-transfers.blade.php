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
                        <th style="width: 30%;">Details</th>
                        <th>Transfer Date</th>
                        <th>Issued By</th>
                        <th>Received By</th>
                        <th>College</th>
                        <th>Reason for transfer</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allTransfers as $transfer)
                    <tr>
                        <td>
                            <div>
                                <div><span class="badge bg-secondary">{{ $transfer->number }}</span></div>
                                <div class="fw-bold mb-2">
                                    {{ $transfer->item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}
                                </div>
                                <div>
                                    <div class="text-muted text-uppercase" style="font-size: 11px;">Quantity Transferred:</div>
                                    <div>{{ $transfer->quantity }}</div>
                                </div>
                                <div>
                                    <div class="text-muted text-uppercase" style="font-size: 11px;">Unit Cost:</div>
                                    <div>₱ {{ number_format($transfer->item->bac_reso_item->quotation->offered_unit_price, 2) }}</div>
                                </div>
                                <div>
                                    <div class="text-muted text-uppercase" style="font-size: 11px;">Total Cost:</div>
                                    <div>₱ {{ number_format($transfer->item->bac_reso_item->quotation->offered_unit_price * $transfer->quantity, 2) }}
                                    </div>
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
                            {{ $transfer->receivers[count($transfer->receivers) - 1]->end_user->position->name }}
                        </td>
                        <td>
                            {{ $transfer->item->transaction->branch->branch_name }}
                        </td>
                        <td>{{ $transfer->reason ?? "n/a" }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <x-slot:additional_script>
            @include('layout/datatable', ['tableId' => 'all-property-transfers'])
            </x-slot>
</x-dashboard-layout>