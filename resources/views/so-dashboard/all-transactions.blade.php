<x-dashboard-layout>
    <x-slot:title>
        All Transactions
        </x-slot>

        @php
        $breadcrumb = [
        ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
        ['name' => 'All Transactions'],
        ]
        @endphp

        <x-breadcrumb :breadcrumb="$breadcrumb" />
        <div class="table-responsive">
            <table class="table table-sm table-hover border-dark caption-top" id="all-transactions">
                <caption>All Transactions</caption>
                <thead class="small">
                    <tr>
                        <th></th>
                        <th style="width: 5%;">Type</th>
                        <th style="width: 15%;">No.</th>
                        <th style="width: 15%;">PO No.</th>
                        <th style="width: 10%;">Acquired</th>
                        <th style="width: 10%;">Issued</th>
                        <th style="width: 20%;">Issuer</th>
                        <th style="width: 20%;">Receiver</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allTrans as $tran)
                    <tr>
                        <td>
                            <div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                                <a href="{{ route('tran.single', ['id' => $tran->id]) }}" class="btn btn-secondary"><em class="bi bi-eye-fill"></em></a>
                                @if ($tran->type !== "PAR")
                                <a href="{{ route('ics.print', ['id' => $tran->id]) }}" target="_blank" class="btn btn-secondary"><em
                                        class="bi bi-printer-fill"></em></a>
                                @else
                                <a href="{{ route('par.print', ['id' => $tran->id]) }}" target="_blank" class="btn btn-secondary"><em
                                        class="bi bi-printer-fill"></em></a>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if ($tran->type === "ICSL")
                            <span class="badge text-bg-secondary"><em class="bi bi-caret-down-fill"></em> ICS</span>
                            @endif
                            @if ($tran->type === "ICSH")
                            <span class="badge text-bg-primary"><em class="bi bi-caret-up-fill"></em> ICS</span>
                            @endif
                            @if ($tran->type === "PAR")
                            <span class="badge text-bg-success">PAR</span>
                            @endif
                        </td>
                        <td>{{ $tran->number }}</td>
                        <td>{{ $tran->purchase_order->po_number }}</td>
                        <td>{{ date('Y-m-d', strtotime($tran->date_acquired)) }}</td>
                        <td>{{ date('Y-m-d', strtotime($tran->date_issued)) }}</td>
                        <td>
                            @foreach ($tran->issuers as $issuer)
                            <div>{{ $issuer->employee->first_name }} {{ $issuer->employee->middle_name }} {{ $issuer->employee->last_name }} <span
                                    class="small badge rounded-pill text-bg-secondary">{{ $issuer->employee->position->name }}</span></div>
                            @endforeach
                        </td>
                        <td>
                            @foreach ($tran->receivers as $receiver)
                            <div>{{ $receiver->end_user->first_name }} {{ $receiver->end_user->middle_name }} {{ $receiver->end_user->last_name }}
                                <span class="small badge rounded-pill text-bg-secondary">{{ $receiver->end_user->position->name }} / {{
                                    $receiver->end_user->branch->branch_name }}</span>
                            </div>
                            @endforeach
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <x-slot:additional_script>
            @include('layout/datatable', ['tableId' => 'all-transactions'])
            </x-slot>
</x-dashboard-layout>