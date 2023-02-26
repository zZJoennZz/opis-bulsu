<x-dashboard-layout>
    <x-slot:title>
        All BAC Resolution ({{getPpmpYear()}})
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'BAC Resolution'],
        ]
    @endphp

    <a href="{{ route('bac-reso.add') }}?step=1" class="float-end btn btn-sm btn-primary"><em class="bi bi-file-earmark-text-fill"></em> Generate BAC</a>
    <x-breadcrumb :breadcrumb="$breadcrumb" />
    
    <div class="table-responsive">
        <table id="bac-reso-table" class="table table-sm caption-top">
            <caption>BAC Resolution List <span class="badge bg-primary">{{ getPpmpYear() }}</span></caption>
            <thead>
                <tr>
                    <th style="width: 50%;">Company</th>
                    <th>Date/Time Created</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            @foreach ($bac_reso as $bac)
                <tbody>
                    <tr>
                        <td>{{$bac->name}}</td>
                        <td>{{date_format($bac->created_at, 'Y/m/d h:i:s A')}}</td>
                        <td class="text-end">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="{{ route('bac-reso.single') }}/{{$bac->id}}" class="btn btn-sm btn-primary"><em class="bi bi-folder2-open"></em> View</a>
                                <button type="button" class="btn btn-sm btn-danger"><em class="bi bi-trash-fill"></em> Delete</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            @endforeach
        </table>
    </div>
    
    <x-slot:additional_script>
        @include('layout/datatable', ['tableId' => 'bac-reso-table'])
    </x-slot>
</x-dashboard-layout>