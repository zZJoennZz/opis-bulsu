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
        <table class="table table-sm table-hover border-dark caption-top">
            <caption>All Transactions</caption>
            <thead class="small">
                <tr>
                    <th>Type</th>
                    <th>Number</th>
                    <th>PO No.</th>
                    <th>Acquired On</th>
                    <th>Issued On</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($allTrans as $tran)
                    <tr>
                        <td>
                            @if ($tran->type === "ICSL")
                                ICS <span class="badge bg-secondary"><em class="bi bi-caret-down-fill"></em> Low Value</span>
                            @endif
                            @if ($tran->type === "ICSH")
                                ICS <span class="badge bg-secondary"><em class="bi bi-caret-up-fill"></em> High Value</span>
                            @endif
                            @if ($tran->type === "PAR")
                                PAR
                            @endif
                        </td>
                        <td>{{ $tran->number }}</td>
                        <td>{{ $tran->purchase_order->po_number }}</td>
                        <td>{{ date('Y-m-d', strtotime($tran->date_acquired)) }}</td>
                        <td>{{ date('Y-m-d', strtotime($tran->date_issued)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <x-slot:additional_script>

    </x-slot>
</x-dashboard-layout>