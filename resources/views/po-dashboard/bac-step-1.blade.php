<x-dashboard-layout>
    <x-slot:title>
        Prepare BAC Step 1
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'BAC Resolution', 'route' => 'dashboard.show'],
            ['name' => 'Prepare BAC']
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />

    <div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-danger">Notice</h5>
                <p class="card-text fs-3 text-muted text-center">
                    Please make sure to double check before submitting BAC.
                </p>
                <div class="text-end">
                    <a href="{{route('bac-reso.add')}}?step=2" class="btn btn-primary">Confirm <em class="bi bi-chevron-double-right"></em></a>
                </div>
            </div>
        </div>
    </div>

</x-dashboard-layout>