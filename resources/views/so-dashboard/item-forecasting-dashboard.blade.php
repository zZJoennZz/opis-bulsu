<x-dashboard-layout>
    <x-slot:title>
        Item Forecasting Dashboard
    </x-slot>

    @php
    $breadcrumb = [
    ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
    ['name' => 'Item Forecasting'],
    ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    <div class="row">
        <div class="col-12">
            <div class="table-responsive caption-top">
                <caption class="fw-semibold fs-5" style="font-family: 'Segoe UI', Arial, sans-serif;">
                    Item Forecasting Report
                </caption>
                <table class="table table-sm table-bordered border-dark" id="all-items-forecasting" style="font-family: 'Segoe UI', Arial, sans-serif;">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-bold text-center" style="font-size: 1rem;">Item</th>
                            <th style="width: 10%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($all_items as $item)
                            <tr>
                                <td class="align-middle" style="font-size: 0.98rem;">{{ $item->description }}</td>
                                <td>
                                    <a href="{{ route('if.generate', ['id' => $item->id]) }}" class="btn btn-primary w-100 fw-semibold" style="font-size: 0.95rem;">
                                        Open
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <x-slot:additional_script>
        @include('layout/datatable', ['tableId' => 'all-items-forecasting'])
    </x-slot>
</x-dashboard-layout>