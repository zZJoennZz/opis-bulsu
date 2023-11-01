<x-dashboard-layout>
    <x-slot:title>
        Physical Count of Property, Plant and Equipment
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Physical Count of Property, Plant and Equipment'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />

    <div class="card mb-3">
        <div class="card-header">
            Generate Report
        </div>
        <div class="card-body">
            <form action="{{ route('ppe.generate') }}" method="POST">
                @csrf
                <div class="col-12">
                    <div class="mb-3">
                        <label for="end_user" class="form-label">End User</label>
                        <select name="end_user" id="end_user" class="form-select" aria-label="Select end user">
                            <option selected disabled hidden>Open to select end user</option>
                            @foreach ($endUsers as $user)
                                <option value="{{ $user->id }}">{{ $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name }} /// {{ $user->branch->branch_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="ppe_type" class="form-label">Type of PPE</label>
                        <select name="ppe_type" id="ppe_type" class="form-select" aria-label="Select type of PPE">
                            <option selected disabled hidden>Open to select type of PPE</option>
                            @foreach ($eqCodes as $code)
                                <option value="{{ $code->id }}">{{ $code->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button class="btn btn-primary" type="submit">Generate</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12 table-responsive">
            <table class="table table-sm caption-top">
                <caption>Generated Reports</caption>
                <thead>
                    <tr>
                        <th>Date Generated</th>
                        <th>End User</th>
                        <th>Type</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                    @php
                        $content = json_decode($report->content, false, 512, JSON_UNESCAPED_SLASHES);
                    @endphp
                        <tr>
                            <td>{{ Carbon\Carbon::parse($report->created_at)->format('Y-m-d H:i A') }}</td>
                            <td>{{ $content->endUser->first_name . ' ' . $content->endUser->middle_name . ' ' . $content->endUser->last_name }}</td>
                            <td>{{ $content->eqCode->description }}</td>
                            <td><a class="btn btn-sm btn-primary" target="_blank" href="{{ route('ppe.print', ['id' => $report->id]) }}">Print</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <x-slot:additional_script>

    </x-slot>
</x-dashboard-layout>