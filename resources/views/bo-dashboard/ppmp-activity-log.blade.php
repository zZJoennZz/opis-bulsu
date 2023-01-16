@include('layout/header', ['title' => 'PPMP Activity Log | OPIS - BulSU e-PROCUREMENT'])
@if (count($ppmp_histories) > 0)
<div class="for-print d-none d-print-none">
    <div class="row mb-3">
        <div class="col-2"></div>
        <div class="col-8 text-center fw-bold">
            <img src="{{ asset('img/bsu-small-logo.png') }}" alt="bsu logo" width="100" style="float: left;" />
            <div class="h-100 d-flex align-content-center flex-column justify-content-center">
                <div>Republic of Philippines</div>
                <div class="fs-5">Bulacan State University</div>
            </div>
        </div>
        <div class="col-2"></div>
    </div>
    <div class="row mb-5">
        <div class="col-12 text-center fs-4 fw-bold">
            PPMP Request Change Logs of {{ $ppmp_histories[0]->branch_name }}
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table table-sm table-bordered border-dark">
                <thead>
                    <tr>
                        <th style="width: 60%; padding: 0.8rem;" class="text-center">HISTORY</th>
                        <th style="width: 20%; padding: 0.8rem;" class="text-center">Date and Time</th>
                        <th style="width: 20%; padding: 0.8rem;" class="text-center">User</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ppmp_histories as $history)
                        <tr>
                            <td class="p-2">
                                <div class="fw-bold">PPMP Item: {{ $history->product_name }}</div>
                                @foreach(json_decode($history->changes_summary) as $summary)
                                    <div class="mb-2 border p-1 rounded">{{ $summary }}</div>
                                @endforeach
                            </td>
                            <td class='text-center'>{{ $history->created_at }}</td>
                            <td class='text-center'>
                                {{ $history->username }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@include('layout/member_header')
<div class="container-fluid">
    <div class="row">
        @include('layout/sidebar')


        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="pt-3">
                <div class="card">
                    <div class="card-body">
                        @php
                            $bc = [];
                            if (Auth::user()->account_type === 'admin' || Auth::user()->account_type === 'PROCUREMENT_OFFICE') {
                                $bc = [
                                    ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
                                    ['name' => 'New PPMP Requests', 'url' => '/ppmp-approval/' . $ppmp_histories[0]->branches_id],
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
                        @endphp
                        @include('layout/breadcrumb',
                        [
                            'breadcrumbs' => $bc
                        ]
                        )
                        <div class="mb-3">
                            <button class="btn btn-success @if(count($ppmp_histories) <= 0) d-none @endif" onclick="window.print()"><em class="bi bi-printer"></em> Print this log</button>
                        </div>
                        <div class="table-responsive">
                            <table id="ppmp-activity-log" class="table table-sm caption-top">
                                <caption>PPMP Activity Log</caption>
                                <thead>
                                    <tr>
                                        <th style="width: 50%;">Activity</th>
                                        <th style="width: 30%;">Date and Time</th>
                                        <th style="width: 20%;">Show Changes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ppmp_histories as $history)
                                        <tr>
                                            <td>
                                                <div class="fw-bold">PPMP Item: {{ $history->product_name }}</div>
                                                @foreach(json_decode($history->changes_summary) as $summary)
                                                    <div class="mb-2 border p-1 rounded">{{ $summary }}</div>
                                                @endforeach
                                            </td>
                                            <td>{{ $history->created_at }}</td>
                                            <td>
                                                <button class="btn btn-info">Show comparison</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<style>
@media print
{
    body * {
        visibility: hidden;
    }

    .for-print {
        display: block !important;
    }

    .for-print,
    .for-print * {
        visibility: visible;
    }
    .for-print {
        display: block !important;
    }
}
</style>
<link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
@include('layout/datatable', ['tableId' => 'ppmp-activity-log'])
@include('layout/footer')