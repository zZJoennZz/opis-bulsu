<x-dashboard-layout>
    <x-slot:title>
        Purchase Order List
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Purchase Order'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />

    <div class="mb-3">
        <a href="{{ route('po.add') }}" class="btn btn-primary"><em class="bi bi-receipt"></em> New Purchase Order</a>
    </div>

    <div class="table-responsive">
        <table class="table table-sm border-dark caption-top" id="po-table">
            <caption>Purchase Order <span class="badge bg-primary">{{ Auth::user()->ppmp_year }}</span></caption>
            <thead>
                <tr>
                    <th>PO No.</th>
                    <th>Supplier</th>
                    <th>Address</th>
                    <th>Email</th>
                    <th>Contact No.</th>
                    <th>TIN</th>
                    <th>PO Date</th>
                    <th>MOP</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($po_list as $po)
                    <tr>
                        <td>{{ $po->po_number }}</td>
                        <td>{{ $po->canvass_abstract->company->name }}</td>
                        <td>{{ $po->canvass_abstract->company->full_address }}</td>
                        <td>{{ $po->canvass_abstract->company->email_address }}</td>
                        <td>{{ $po->canvass_abstract->company->contact_number }}</td>
                        <td>{{ $po->canvass_abstract->company->tin }}</td>
                        <td>{{ $po->created_at }}</td>
                        <td>{{ $po->mop->name }}</td>
                        <td class="text-end">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="{{ route('po.single') }}/{{$po->id}}" class="btn btn-sm btn-primary"><em class="bi bi-printer-fill"></em></a>
                                <button type="button" class="btn btn-sm btn-danger"><em class="bi bi-trash-fill"></em></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <x-slot:additional_script>
        @include('layout/datatable', ["tableId" => "po-table"])
    </x-slot>
</x-dashboard-layout>