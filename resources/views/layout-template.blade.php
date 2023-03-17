<x-dashboard-layout>
    <x-slot:title>
        TITLE HERE
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>'],
            ['name' => 'Manage End User'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />

    <x-slot:additional_script>

    </x-slot>
</x-dashboard-layout>