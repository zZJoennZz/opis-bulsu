<x-dashboard-layout>
    <x-slot:title>
        All Item Stock Forecasting
        </x-slot>

        @php
        $breadcrumb = [
        ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
        ['name' => 'All Item Stock Forecasting'],
        ]
        @endphp

        <x-breadcrumb :breadcrumb="$breadcrumb" />
        <div class="table-responsive">
            <table class="table table-sm table-hover border-dark caption-top" id="all-item-forecasting">
                <caption>Select item to view the forecasted data</caption>
                <thead class="small">
                    <tr>
                        <th>Item</th>
                        <th>Unit</th>
                        <th>Category</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($all_items as $item)
                        <tr>
                            <td><strong><em>{{ $item->article }}</em></strong>, <em>{{ $item->description }}</em></td>
                            <td>{{ $item->unit->uom }}</td>
                            <td><strong><em>{{ $item->category->group->title }}</em></strong>@if($item->category->description != "N/A"), <strong>{{ $item->category->description }}</strong>@endif</td>
                            <td>
                                <a class="btn btn-primary" href="#">Run <em class="bi bi-arrow-right-short"></em></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div>
            <canvas id="myChart"></canvas>
        </div>
        <x-slot:additional_script>
            @include('layout/datatable', ['tableId' => 'all-item-forecasting'])
            {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                // Example data
                const originalData = @php echo json_encode($data) @endphp;
                const data = @php echo json_encode(exponentialSmoothing($data, $alpha)) @endphp
        
                // Create the chart
                const ctx = document.getElementById('myChart').getContext('2d');
                const myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        // labels: ['2020', '2021', '2022', '2023', '2024', '2025'], // Labels for X-axis
                        labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'], // Labels for X-axis
                        datasets: [{
                            label: 'Original Data',
                            data: originalData,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            fill: false,
                        }, {
                            label: 'Smoothed Data',
                            data: data.smoothed,
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            fill: false,
                        }, {
                            label: 'Forecast',
                            data: [...new Array(data.smoothed.length).fill(null), ...data.forecast], // Forecast area
                            borderColor: 'rgba(255, 159, 64, 1)',
                            backgroundColor: 'rgba(255, 159, 64, 0.2)',
                            fill: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Year'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Utilization'
                                }
                            }
                        }
                    }
                });
            </script> --}}
            </x-slot>
</x-dashboard-layout>