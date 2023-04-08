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

    <div class="mb-3">
        <a href="{{ route('ia.add') }}" class="btn btn-primary"><em class="bi bi-card-text"></em> Add New Report</a>
    </div>
    <table class="table table-sm border-dark caption-top">
        <caption>Inspection and Acceptance Reports List <span class="badge bg-primary">{{ Auth::user()->ppmp_year }}</span></caption>
        <thead>
            <tr>
                <th>I.A.R. No.</th>
                <th>Date</th>
                <th>Supplier/Company</th>
                <th>P.O. No.</th>
                <th class="text-end">Print</th>
            </tr>
        </thead>
        @foreach ($iaa as $i)
            <tr>
                <td>{{ $i->iar_no }}</td>
                <td>{{ $i->iar_date }}</td>
                <td>{{ $i->purchase_order->company->name }}</td>
                <td>{{ $i->purchase_order->po_number }}</td>
                <td class="text-end"><a target="_blank" href="{{ route('ia.single') }}/{{ $i->id }}" class="btn btn-primary"><em class="bi bi-printer-fill"></em></a></td>
            </tr>
        @endforeach
    </table>

    <x-slot:additional_script>
        
    </x-slot>
</x-dashboard-layout>