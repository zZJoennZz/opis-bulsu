<x-dashboard-layout>
    <x-slot:title>
        Request for Quotation
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Request for Quotation'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    <div class="mb-3">
        <a class="btn btn-sm btn-primary" href="{{ route('rfq.add') }}"><em class="bi bi-file-earmark-plus-fill"></em> Create New</a>
    </div>
    <table class="table table-sm border-dark caption-top" id="rfq-table">
        <caption>Request for Quotation List <span class="badge bg-primary">{{ Auth::user()->ppmp_year }}</span></caption>
        <thead>
            <tr>
                <th style="width: 50px;"></ths>
                <th>Quotation No.</th>
                <th>Purchase Request No.</th>
                <th class="text-end">Date Added</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchase_requests as $pr)
                <tr>
                    <td><a target="_blank" href="{{ route('rfq.print', ["id" => $pr->rfq->id]) }}" class="btn btn-sm btn-outline-primary"><em class="bi bi-printer-fill"></em></a></td>
                    <td>{{ $pr->rfq->quotation_number }}</td>
                    <td>{{ $pr->pr_number }}</td>
                    <td>{{ $pr->rfq->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <x-slot:additional_script>
        @include('layout/datatable', ['tableId' => 'rfq-table'])
    </x-slot>
</x-dashboard-layout>