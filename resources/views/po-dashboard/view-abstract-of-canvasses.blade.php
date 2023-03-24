<x-dashboard-layout>
    <x-slot:title>
        Abstract of Canvasses
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>'],
            ['name' => 'Abstract of Canvasses <span class="badge bg-primary">'. Auth::user()->ppmp_year .'</span>'],
        ]
    @endphp
    <span class="float-end small"># of records: <span class="badge text-bg-secondary">{{ count($aocs) }}</span></span>
    <x-breadcrumb :breadcrumb="$breadcrumb" />
    <div class="mb-3">
        <a href="{{ route('aoc.add') }}" class="btn btn-primary"><em class="bi bi-file-earmark-ruled-fill"></em> Generate</a>
    </div>
    <div class="table-responsive">
        <table class="table table-sm border-dark" id="aocs-table">
            <thead>
                <tr>    
                    <th></th>
                    <th style="width: 50%;">PR Number</th>
                    <th style="width: 45%;" class="text-end">Date Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($aocs as $aoc)
                    <tr>
                        <td><a href="#" class="btn btn-success btn-sm"><em class="bi bi-pencil-square"></em></a></td>
                        <td>{{ $aoc->pr->pr_number }}</td>
                        <td class="text-end">{{ $aoc->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <x-slot:additional_script>
        @include('layout/datatable', ["tableId" => "aocs-table"])
    </x-slot>
</x-dashboard-layout>