<x-dashboard-layout>
    <x-slot:title>
        Dashboard
    </x-slot>

    @php
        $breadcrumb = [['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show']]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    @if (Auth::user()->account_type === "admin" || Auth::user()->account_type === "END_USER")
        @include('layout/enduser_dashboard')
        <hr />
    @endif

    @if (Auth::user()->account_type === "admin" || Auth::user()->account_type === "BUDGET_OFFICE")
        @include('layout/bo_dashboard')
        <hr />
    @endif

    @if (Auth::user()->account_type === "admin" || Auth::user()->account_type === "PROCUREMENT_OFFICE")
        @include('layout/po_dashboard')
        <hr />
    @endif

    @if (Auth::user()->account_type === "admin" || Auth::user()->account_type === "SUPPLY_OFFICE")
        @include('layout/so_dashboard')
        <hr />
    @endif
    <x-slot:additional_script>
        {{-- <style>
            .ppmpCard {
                transition: ease-in-out all 200ms;
                border: 1px solid transparent;
            }
            .ppmpCard:hover {
                box-shadow: none !important;
                border-bottom: 1px solid rgb(209, 209, 209) !important;
            }
        </style> --}}
    </x-slot>
</x-dashboard-layout>