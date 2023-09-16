<x-dashboard-layout>
    <x-slot:title>
        Select maintenance form
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Form Type'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    
    <div class="row mb-3">
        <div class="col-12">
            <h1 class="h5 text-uppercase text-secondary">Form Type</h1>
        </div>
    </div>

    <div class="row text-center">
        <div class="col-sm-12 col-md-6">
            <button class="btn btn-primary btn-lg w-100" type="button"><em class="bi bi-tools"></em> Maintenance</button>
        </div>
        <div class="col-sm-12 col-md-6">
            <button class="btn btn-secondary btn-lg w-100" type="button"><em class="bi bi-trash-fill"></em> Disposal</button>
        </div>
    </div>
    
    <x-slot:additional_script>

    </x-slot>
</x-dashboard-layout>