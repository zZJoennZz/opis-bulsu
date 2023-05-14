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
                    <th style="width: 70px;"></th>
                    <th>PO No.</th>
                    <th>PO Date</th>
                    <th>MOP</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($po_list as $po)
                    <tr>
                        <td>
                            <form action="{{ route('po.delete') }}/{{ $po->id }}" method="POST" onsubmit="return confirm('Warning: Deleting this purchase order will permanently remove all related ALOBS and inspection and acceptance reports for this record. Proceeding with the deletion cannot be undone. Are you absolutely certain you wish to proceed with this action?')">
                                @csrf
                                @method("DELETE")
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a target="_blank" href="{{ route('po.single') }}/{{$po->id}}" class="btn btn-sm btn-primary"><em class="bi bi-printer-fill"></em></a>
                                    <button type="submit" class="btn btn-sm btn-danger"><em class="bi bi-trash-fill"></em></button>
                                </div>
                            </form>
                        </td>
                        <td>{{ $po->po_number }}</td>
                        <td>{{ $po->created_at }}</td>
                        <td>{{ $po->mop->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <x-slot:additional_script>
        @include('layout/datatable', ["tableId" => "po-table"])
    </x-slot>
</x-dashboard-layout>