<x-dashboard-layout>
    <x-slot:title>
        All Item Stock Forecasting
        </x-slot>

        @php
        $breadcrumb = [
        ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
        ['name' => 'All Item Stock Forecasting', 'route' => 'if.all'],
        ['name' => 'About the Forecasting Model'],
        ]
        @endphp

        <x-breadcrumb :breadcrumb="$breadcrumb" />
        <div class="px-5">
            <div class="mb-4">
                <h1>About the Model</h1>
                <p>
                    The forecasting function of this web application utilizes the simple exponential smoothing method. A forecasting technique that employs a weighted moving average for predictions, where the weights assigned diminish exponentially for periods farther in the past. It employs a parameter referred to as “alpha.” The function of alpha is to harmonize the significance of new and old data. Possible values for alpha can be found on the slider (located at the top-right), and by modifying the alpha value with the slider, your forecasts can become either more responsive to recent changes or more consistent over time, offering a decision-support tool for managing stocks. This will produce a chart illustrating the real data from the PPMP for particular items (only consumable and semi-expendable items) alongside the projected data for those items for every year. (See images below)
                </p>
                <img src="{{ asset("img/Figure1.png") }}" alt="BSU Small Logo" class="d-block mb-3 m-auto border border-primary-subtle rounded-5 shadow" style="width: 100%; object-fit: contain;" />
                <div class="text-center small fst-italic text-secondary">Figure 1. A clear picture of the forecasted data for the year 2026 and used the past three (3) years as basis to come up with the original data.</div>
            </div>
            <div class="mb-4">
                <p>
                    Forecasted data for each year:
                </p>
                <img src="{{ asset("img/Figure2.png") }}" alt="BSU Small Logo" class="d-block mb-3 m-auto border border-primary-subtle rounded-5 shadow" style="width: 100%; object-fit: contain;" />
                    <div class="text-center small fst-italic text-secondary">Figure 2. Forecasted data for year 2024. Based on the original data of year 2023 (200 items), it produced a forecasted data for year 2024 (200 items).</div>
            </div>
            <div class="mb-4">
                <img src="{{ asset("img/Figure3.png") }}" alt="BSU Small Logo" class="d-block mb-3 m-auto border border-primary-subtle rounded-5 shadow" style="width: 100%; object-fit: contain;" />
                    <div class="text-center small fst-italic text-secondary">Figure 3. Forecasted data for year 2025. Based on the original data of year 2024 (200 items), it produced a forecasted data for year 2025 (200 items).</div>
            </div>
            <div class="mb-4">
                <img src="{{ asset("img/Figure4.png") }}" alt="BSU Small Logo" class="d-block mb-3 m-auto border border-primary-subtle rounded-5 shadow" style="width: 100%; object-fit: contain;" />
                    <div class="text-center small fst-italic text-secondary">Figure 4. Forecasted data for year 2026. Based on the original data of year 2025 (350 items), it produced a forecasted data for year 2026 (335 items).</div>
            </div>
            <div class="mb-4">
                <p>
                    This is where the role and functionality of alpha takes place, using its formula and pre-defined algorithm, it balances the importance of new data against the old data and somehow smoothens the curve on an average point to establish a forecast for succeeding years that can help the decision-making aspect of the management.
                </p>
            </div>
        </div>
</x-dashboard-layout>