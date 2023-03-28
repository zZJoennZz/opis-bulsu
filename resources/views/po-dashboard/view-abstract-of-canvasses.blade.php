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
        <table class="table table-sm border-dark caption-top" id="aocs-table">
            <caption>Abstract of Canvass <span class="badge bg-primary">{{ getPpmpYear() }}</span></caption>
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
                        <td>
                            <div class="btn-group" role="group" aria-label="{{ $aoc->pr->pr_number }} options">
                                <a href="{{ route('aoc.single', ['id' => $aoc->id]) }}" class="btn btn-outline-primary btn-sm"><em class="bi bi-eye-fill"></em></a>
                                <a href="#" class="btn btn-outline-danger btn-sm"><em class="bi bi-trash"></em></a>
                            </div>
                        </td>
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