<x-dashboard-layout>
    <x-slot:title>
        All BAC Resolution ({{getPpmpYear()}})
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'BAC Resolution <span class="badge bg-primary">' . getPpmpYear() . '</span>'],
        ]
    @endphp

    <div class="h5 card-title"> <span class="float-end small"># of records: <span class="badge text-bg-secondary">{{ count($bac_resos) }}</span></span></div>

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    
    {{-- <div class="mb-3">
        <a href="{{ route('bac-reso.add') }}" class="btn btn-primary"><em class="bi bi-file-spreadsheet-fill"></em> Generate BAC Resolution</a>
    </div> --}}

    <div class="table-responsive">
        <table id="bac-reso-table" class="table table-sm caption-top border-dark">
            <caption>BAC Resolution List <span class="badge bg-primary">{{ getPpmpYear() }}</span></caption>
            <thead>
                <tr>
                    <th class="text-start"></th>
                    <th style="width: 5%;">Status</th>
                    <th style="width: 5%;">Type</th>
                    <th style="width: 30%;">BAC Resolution No.</th>
                    <th style="width: 30%;">Purchase Request No.</th>
                    <th style="width: 25%;">Date Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bac_resos as $bac_reso)
                    @if ($bac_reso->abstract_of_canvass->is_draft === 0)
                        <tr>
                            <td>
                                <a {{ !$bac_reso->is_draft ? 'target="_blank"' : "" }} href="{{ route('bac-reso.single', ['id' => $bac_reso->id]) }}" class="btn btn-outline-primary btn-sm"><em class="bi bi-eye-fill"></em></a>
                            </td>
                            <td><span class="badge bg-{{ $bac_reso->is_draft ? "secondary" : "primary" }}">{{ $bac_reso->is_draft ? "Draft" : "Done" }}</span></td>
                            <td><span class="badge bg-primary">{{ $bac_reso->abstract_of_canvass->type === "BY_ITEM" ? 'By Item' : 'By Lot' }}</span></td>
                            <td>{{ $bac_reso->b_a_c_reso_number }}</td>
                            <td>{{ $bac_reso->abstract_of_canvass->pr->pr_number }}</td>
                            <td>{{ date("Y-m-d h:i A", strtotime($bac_reso->created_at)) }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
    
    <x-slot:additional_script>
        @include('layout/datatable', ['tableId' => 'bac-reso-table'])
    </x-slot>
</x-dashboard-layout>