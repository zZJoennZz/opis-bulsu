<x-dashboard-layout>
    <x-slot:title>
        All Item Stock Forecasting
        </x-slot>

        @php
        $breadcrumb = [
        ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
        ['name' => 'All Item Stock Forecasting', 'route' => 'if.all'],
        ['name' => 'Forecast for ' . $item_forecast->item_detail->description, 'url' => '/item-forecasting/'. $item_forecast->item_details_id .'/generate'],
        ['name' => 'View Generated Forecast for ' . $item_forecast->item_detail->description]
        ]
        @endphp

        <x-breadcrumb :breadcrumb="$breadcrumb" />

        <div class="container mt-4">
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h4 class="card-title mb-3">
                                <i class="bi bi-bar-chart-line-fill me-2"></i>
                                Forecasted Quantity
                            </h4>
                            <p class="mb-1">
                                <strong>Item:</strong> {{ $item_forecast->item_detail->description }}
                            </p>
                            <p class="mb-1">
                                <strong>Unit:</strong> {{ $item_forecast->item_detail->unit->uom }}
                            </p>
                            <p class="mb-1">
                                <strong>Forecast Group:</strong>
                                <span class="badge bg-info text-dark">
                                    @if($item_forecast->group === "MONTH") Monthly @else Yearly @endif
                                </span>
                            </p>
                            <div class="mt-3">
                                <span class="fw-bold">Forecast for 
                                    @if($item_forecast->group === "MONTH") month @else year @endif 
                                    <span id="forecast-text" class="text-primary"></span>:
                                </span>
                                <span class="badge bg-primary fs-5" id="forecast_num"></span>
                                <span class="ms-2 text-muted">{{ $item_forecast->item_detail->unit->uom }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-graph-up"></i> Forecast Chart</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-responsive" style="min-height:350px;">
                                <canvas id="myChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <x-slot:additional_script>
            {{-- @include('layout/datatable', ['tableId' => 'generated-forecasts']) --}}
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                const data = {!! $item_forecast->data !!}

                const originalData = data.combined.map(item => item.original);
                const smoothedData = data.combined.map(item => item.smoothed);
                const labels = data.combined.map(item => item.key);

                // Calculate max utilization value from both datasets
                const maxUtilization = Math.max(
                    ...originalData,
                    ...smoothedData
                );
                // Set y-axis max to 10% above the max utilization
                const yAxisMax = Math.ceil(maxUtilization * 1.1);

                @if($item_forecast->group === "YEAR")
                document.getElementById("forecast-text").innerHTML = labels[labels.length - 1] + 1;
                @else
                let lastLabel = labels[labels.length - 1];

                // Split the label into year and month
                let parts = lastLabel.split("-");
                let year = parseInt(parts[0]);
                let month = parseInt(parts[1]);

                // Increment the month by 1
                month += 1;

                // If the month exceeds 12 (December), roll over to the next year
                if (month > 12) {
                    month = 1; // Reset to January
                    year += 1; // Increment the year
                }

                // Format the new label as "YYYY-MM" with leading zero for the month if necessary
                let newLabel = year + "-" + (month < 10 ? "0" + month : month);

                // Set the new label in the forecast-text element
                document.getElementById("forecast-text").innerHTML = newLabel;
                @endif

                document.getElementById("forecast_num").innerHTML = Math.round(smoothedData[smoothedData.length-1]);

                // Create the chart
                const ctx = document.getElementById('myChart').getContext('2d');
                const myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels, // Labels for X-axis
                        datasets: [{
                            label: 'Original Data',
                            data: originalData,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            fill: false,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            tension: 0.3
                        }, {
                            label: 'Forecasted Data',
                            data: smoothedData,
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            fill: false,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    font: {
                                        size: 14
                                    }
                                }
                            },
                            tooltip: {
                                enabled: true,
                                mode: 'index',
                                intersect: false
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Period'
                                },
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Utilization (QTY)'
                                },
                                grid: {
                                    color: 'rgba(200,200,200,0.2)'
                                },
                                min: 0, // Always start at 0
                                max: yAxisMax // Always max 10% above max utilization
                            }
                        }
                    }
                });
            </script>
            </x-slot>
</x-dashboard-layout>