<x-dashboard-layout>
    <x-slot:title>
        Generate BAC Resolution
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'BAC Resolution <span class="badge bg-primary">' . getPpmpYear() . '</span>', 'route' => 'bac-reso.all'],
            ['name' => 'Generate']
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    
    ASDASDASD
    
    <x-slot:additional_script>
        
    </x-slot>
</x-dashboard-layout>