<x-dashboard-layout>
    <x-slot:title>
        Keepers
        </x-slot>

        @php
        $breadcrumb = [
        ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
        ['name' => 'Keepers'],
        ]
        @endphp

        <x-breadcrumb :breadcrumb="$breadcrumb" />
        <div class="table-responsive">
            <table class="table table-sm table-hover border-dark caption-top" id="all-endusers">
                <caption>All end users keeping a property/item</caption>
                <thead class="small">
                    <tr>
                        <th></th>
                        <th style="width: 50%;">Name</th>
                        <th style="width: 20%;">Office/Campus</th>
                        <th style="width: 15%;">Position</th>
                        <th class="text-end" style="width: 10%;"># of items</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allEndUsers as $endUser)
                    <tr>
                        <td><a href="{{ route('end_users.single', ['userId' => $endUser->id]) }}" class="btn btn-sm btn-primary"><em
                                    class="bi bi-eye-fill"></em></a></td>
                        <td>{{ $endUser->first_name . ' ' . $endUser->middle_name . ' ' . $endUser->last_name }}</td>
                        <td>{{ $endUser->branch->branch_name }}</td>
                        <td>{{ $endUser->position->name }}</td>
                        <td class="text-end">{{ count($endUser->keepers) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <x-slot:additional_script>
            @include('layout/datatable', ['tableId' => 'all-endusers'])
            </x-slot>
</x-dashboard-layout>