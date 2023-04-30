<x-dashboard-layout>
    <x-slot:title>
        PPMP Activity Log
    </x-slot>

    @php
        $bc = [];
        if (Auth::user()->account_type === 'admin' || Auth::user()->account_type === 'PROCUREMENT_OFFICE') {
            $bc = [
                ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
                ['name' => 'New PPMP Requests', 'url' => '/ppmp-approval/' . $branch_id],
                ['name' => 'PPMP Activity Log']
            ];
        } else if (Auth::user()->account_type === 'END_USER') {
            $bc = [
                ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
                ['name' => 'PPMP Cart', 'route' => 'ppmp-cart.get'],
                ['name' => 'PPMP Activity Log']
            ];
        } else if (Auth::user()->account_type === 'BUDGET_OFFICE') {
            $bc = [
                ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
                ['name' => 'PPMP Activity Log'],
            ];
        }
        $breadcrumb = $bc
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />

    <div class="mb-3">
        <a class="btn btn-success @if(count($ppmp_histories) <= 0) d-none @endif" href="{{ route('ppmp-activity-log.print', ['branch_id' => $branch_id]) }}" target="_blank"><em class="bi bi-printer"></em> Print this log</a>
    </div>
    <div class="my-3">
        <div class="fs-3 fw-bold">{{ $ppmp_histories[0]->ppmp->branch->branch_name }} PPMP Activity Logs <span class="badge bg-primary">{{ getPpmpYear() }}</span></div>
    </div>
    <div class="table-responsive">
        <table id="ppmp-activity-log" class="table table-sm caption-top">
            <caption>PPMP Activity Log</caption>
            <thead>
                <tr>
                    <th style="width: 50%;">Activity</th>
                    <th style="width: 30%;" class="text-end">Date and Time</th>
                    {{-- <th style="width: 20%;">Show Changes</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($ppmp_histories as $history)
                    <tr>
                        <td>
                            <div class="fw-bold">PPMP Item: {{ $history->ppmp->item_detail->description }}</div>
                            @foreach(json_decode($history->changes_summary) as $summary)
                                <div class="mb-2 border p-1 rounded">{{ $summary }}</div>
                            @endforeach
                            <div class="my-2 text-secondary small fst-italic">
                                <span class="fw-bold">Action by: </span> {{ $history->changes_record_by->profile->first_name }} {{ $history->changes_record_by->profile->last_name }}
                            </div>
                        </td>
                        <td>{{ $history->created_at }}</td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <x-slot:additional_script>
        @include('layout/datatable', ['tableId' => 'ppmp-activity-log'])
    </x-slot>
</x-dashboard-layout>