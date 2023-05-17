<x-dashboard-layout>
    <x-slot:title>
        Inspection and Acceptance
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Inspection and Acceptance Report'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    {{--
    <div class="mb-3">
        <a href="{{ route('ia.add') }}" class="btn btn-primary"><em class="bi bi-card-text"></em> Add New Report</a>
    </div> --}}
    <table class="table table-sm border-dark caption-top" id="iaa-table">
        <caption>Inspection and Acceptance Reports List <span class="badge bg-primary">{{ Auth::user()->ppmp_year }}</span></caption>
        <thead>
            <tr>
                <th style="width: 50px;"></th>
                <th>P.O. No.</th>
                <th>I.A.R. No.</th>
                <th>Date</th>
                <th>Supplier/Company</th>
            </tr>
        </thead>
        @foreach ($iaa as $i)
            <tr>
                <td>
                    @if ($i->is_draft)
                        <a href="{{ route('iaa.single') }}/{{ $i->id }}" class="btn btn-sm btn-outline-primary"><em class="bi bi-eye-fill"></em></a>
                    @else
                        <a target="_blank" href="{{ route('ia.single') }}/{{ $i->id }}" class="btn btn-sm btn-outline-primary"><em class="bi bi-printer-fill"></em></a>
                    @endif
                </td>
                <td><span class="badge bg-{{ $i->is_draft ? "secondary" : "primary" }}">{{ $i->is_draft ? "Draft" : "Done" }}</span> {{ $i->purchase_order->po_number }}</td>
                <td>{{ $i->iar_no }}</td>
                <td>{{ $i->iar_date }}</td>
                <td>{{ $i->purchase_order->company->name }}</td>
            </tr>
        @endforeach
    </table>

    <x-slot:additional_script>
        @include('layout/datatable', ['tableId' => 'iaa-table'])
    </x-slot>
</x-dashboard-layout>