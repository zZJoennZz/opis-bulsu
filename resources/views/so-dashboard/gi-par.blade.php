<x-dashboard-layout>
    <x-slot:title>
        Property Acknowledgement Receipt - General Inventory
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Property Acknowledgement Receipt - General Inventory'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    
    <div class="row mb-2">
        <div class="col-12">
            <span class="small fw-bold text-uppercase">Generate Report for</span>
        </div>
    </div>
    <form method="POST" action="{{ route('gi-par.post') }}">
        <div class="row mb-4">
            @csrf
            <div class="col-md-11 col-sm-12 mt-1">
                <select class="form-select" name="end_user_id" id="end_user_id" aria-label="Select end user to generate report">
                    <option selected hidden disabled>Select end user</option>
                    @foreach ($endUsers as $user)
                        <option value="{{ $user->id }}">{{ $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name }} /// {{ $user->branch->branch_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1 col-sm-12 mt-1">
                <button class="btn btn-primary w-100"><em class="bi bi-arrow-right-short"></em></button>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-sm table-hover caption-top" id="gi-par-tbl">
            <caption>Generated Reports for Property Acknowledgement Receipt (PAR) - General Inventory</caption>
            <thead>
                <tr>
                    <th style="width: 30%">End User</th>
                    <th style="width: 30%">College/Campus</th>
                    <th style="width: 20%">Date Generated</th>
                    <th style="width: 10%">Actions</th>
                </tr>
            </thead>
            <tbody>
                {{-- <tr>
                    <td>Joenn Aquilino</td>
                    <td>Main Office</td>
                    <td>2021-02-01</td>
                    <td>
                        <button class="btn btn-primary btn-sm">Print</button>
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </td>
                </tr> --}}
                @foreach ($reports as $report)
                @php
                    $content = json_decode($report->content, false, 512, JSON_UNESCAPED_SLASHES);
                @endphp
                <tr>
                    <td>{{ $content->end_user->first_name . ' ' . $content->end_user->middle_name . ' ' . $content->end_user->last_name }}</td>
                    <td>{{ $content->end_user->branch->branch_name }}</td>
                    <td>{{ Carbon\Carbon::parse($report->created_at)->format('Y-m-d H:i A') }}</td>
                    <td>
                        <a href="{{ route('gi-par.print', ['snapshot_id' => $report->id]) }}" target="_blank" class="btn btn-primary btn-sm">Print</a>
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- {
        'end_user': 1,
        'content': [
            {
                'qty': 3,
                'unit': 'pack',
                'description': 'Test Product',
                'date_acquired': '2021-02-01',
                'unit_value': 30000,
                'remarks': 'Test',
            },
            {
                'qty': 3,
                'unit': 'pack',
                'description': 'Test Product',
                'date_acquired': '2021-02-01',
                'unit_value': 30000,
                'remarks': 'Test',
            },
            {
                'qty': 3,
                'unit': 'pack',
                'description': 'Test Product',
                'date_acquired': '2021-02-01',
                'unit_value': 30000,
                'remarks': 'Test',
            },
            {
                'qty': 3,
                'unit': 'pack',
                'description': 'Test Product',
                'date_acquired': '2021-02-01',
                'unit_value': 30000,
                'remarks': 'Test',
            },
        ],
    } --}}

    <x-slot:additional_script>
    @include('layout/datatable', ["tableId" => "gi-par-tbl"])
    </x-slot>
</x-dashboard-layout>