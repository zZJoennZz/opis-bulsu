<x-dashboard-layout>
    <x-slot:title>
        All Item Stock Forecasting
        </x-slot>

        @php
        $breadcrumb = [
        ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
        ['name' => 'All Item Stock Forecasting', 'route' => 'if.all'],
        ['name' => 'Forecast for ' . $item->description]
        ]
        @endphp

        <x-breadcrumb :breadcrumb="$breadcrumb" />
        <div>
            <div class="mb-3">
                <h1 class="fs-4">Forecasting for <span class="badge text-bg-secondary">{{ $item->description }}</span></h1>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="table-responsive">
                        <table id="generated-forecasts" class="caption-top table table-hover table-sm table-bordered border-dark">
                            <caption>Generated Forecasts</caption>
                            <thead>
                                <tr>
                                    <th style="width: 70%;">Details</th>
                                    <th>Date Generated</th>
                                    <th style="width: 30px;">View</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item->forecasts as $forecast)
                                    <tr>
                                        <td>
                                            <div><strong>Grouped by:</strong> {{ $forecast->group }}</div>
                                            <div><strong>Alpha:</strong> {{ $forecast->alpha * 10 }}/<em>10</em></div>
                                            <div><strong>Date Range:</strong> {{ $forecast->from_date }} to {{ $forecast->to_date }}</div>
                                        </td>
                                        <td style="font-size: 12px;">{{ formatDate($forecast->created_at) }}</td>
                                        <td>
                                            <a href="{{ route('if.view', ['item_details_id' => $forecast->item_details_id, 'id' => $forecast->id]) }}" class="btn btn-secondary"><em class="bi bi-folder2-open"></em></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="mb-4">
                        <h2 class="fs-5">Generate Forecasting</h2>
                        <em>Complete the form below</em>
                    </div>
                    <div class="mb-3">
                        <form method="POST" action="{{ route("if.process", ["id" => $item->id]) }}">
                            @csrf
                            <div class="mb-3">
                                <label for="alpha" class="form-label">Forecast Alpha
                                    <span class="ms-2 badge bg-info" id="alphaValue">5</span>
                                    <em class="bi bi-info-circle" data-bs-toggle="tooltip" title="Adjust the weight for recent data."></em>
                                </label>
                                <input type="range" class="form-range" min="1" max="10" id="alpha" name="alpha" aria-describedby="alphaHelp" value="5" required>
                                <div class="d-flex justify-content-between px-1" style="font-size: 0.9em;">
                                    @for ($i = 1; $i <= 10; $i++)
                                        <span>{{ $i }}</span>
                                    @endfor
                                </div>
                                <div id="alphaHelp" class="form-text">Alpha indicates the weight of the most recent data. A value closer to max range emphasizes recent observations, while a value closer to min range smooths the forecast by relying more on historical trends.</div>
                            </div>
                            <div class="mb-3">
                                <label for="group" class="form-label">Data Group
                                    <em class="bi bi-info-circle" data-bs-toggle="tooltip" title="Choose how to group the data for forecasting."></em>
                                </label>
                                <select id="group" name="group" class="form-select" aria-label="Select data group" required>
                                    <option selected disabled value="">Select data group</option>
                                    <option value="MONTH">By month</option>
                                    <option value="YEAR">By year</option>
                                </select>
                                <div class="invalid-feedback">Please select a data group.</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Date Range
                                    <em class="bi bi-info-circle" data-bs-toggle="tooltip" title="Select the date range for the forecast."></em>
                                </label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="date" name="from_date" id="from_date" aria-label="From date" class="form-control" placeholder="From" required autofocus>
                                        <div class="invalid-feedback">Please select a start date.</div>
                                    </div>
                                    <div class="col-6">
                                        <input type="date" name="to_date" id="to_date" aria-label="To date" class="form-control" placeholder="To" required>
                                        <div class="invalid-feedback">Please select an end date.</div>
                                    </div>
                                </div>
                                <div id="dateRangeHelp" class="form-text">At least 2 group of data to generate a report.</div>
                            </div>
                            <div>
                                <button class="btn btn-primary" type="submit">Generate</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <x-slot:additional_script>
            @include('layout/datatable', ['tableId' => 'generated-forecasts'])
            
            </x-slot>
</x-dashboard-layout>
<script>
    // Show alpha value beside slider
    document.addEventListener('DOMContentLoaded', function() {
        var alpha = document.getElementById('alpha');
        var alphaValue = document.getElementById('alphaValue');
        if(alpha && alphaValue) {
            alphaValue.textContent = alpha.value;
            alpha.addEventListener('input', function() {
                alphaValue.textContent = alpha.value;
            });
        }
        // Enable Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>