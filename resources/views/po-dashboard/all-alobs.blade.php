<x-dashboard-layout>
    <x-slot:title>
        Allotment and Obligation Slip
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Allotment and Obligation Slip'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    {{--
    <div class="mb-3">
        <a href="{{ route('ia.add') }}" class="btn btn-primary"><em class="bi bi-card-text"></em> Add New Report</a>
    </div> --}}
    <table class="table table-sm border-dark caption-top" id="alobs-table">
        <caption>Allotment and Obligation Slip Reports List <span class="badge bg-primary">{{ Auth::user()->ppmp_year }}</span></caption>
        <thead>
            <tr>
                <th style="width: 50px;"></ths>
                <th>P.O. No.</th>
                <th class="text-end">Date Added</th>
            </tr>
        </thead>
        @foreach ($alobs as $i)
            <tr>
                <td>
                    @if ($i->is_draft)
                        <a href="{{ route('alobs.single') }}/{{ $i->id }}" class="btn btn-sm btn-outline-primary"><em class="bi bi-eye-fill"></em></a>
                    @else
                        <a target="_blank" href="{{ route('alobs.print') }}/{{ $i->id }}" class="btn btn-sm btn-outline-primary"><em class="bi bi-printer-fill"></em></a>
                    @endif
                </td>
                <td><span class="badge bg-{{ $i->is_draft ? "secondary" : "primary" }}">{{ $i->is_draft ? "Draft" : "Done" }}</span> {{ $i->purchase_order->po_number }}</td>
                <td class="text-end">{{ date('Y-m-D h:i:s A', strtotime($i->created_at)) }}</td>
            </tr>
        @endforeach
    </table>

    <x-slot:additional_script>
        @include('layout/datatable', ['tableId' => 'alobs-table'])
    </x-slot>
</x-dashboard-layout>