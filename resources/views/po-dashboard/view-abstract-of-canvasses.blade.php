<x-dashboard-layout>
    <x-slot:title>
        Abstract of Canvasses
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Abstract of Canvasses <span class="badge bg-primary">'. Auth::user()->ppmp_year .'</span>'],
        ]
    @endphp
    <span class="float-end small"># of records: <span class="badge text-bg-secondary">{{ count($aocs) }}</span></span>
    <x-breadcrumb :breadcrumb="$breadcrumb" />
    <div class="mb-3">
        <a href="{{ route('aoc.add') }}" class="btn btn-primary"><em class="bi bi-file-earmark-ruled-fill"></em> Generate Abstract of Canvass</a>
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
                            <form action="{{ route('aoc.delete') }}/{{ $aoc->id }}" method="POST" onsubmit="return confirm('Are you sure to delete this?')">
                                @csrf
                                @method('DELETE')
                                <div class="btn-group" role="group" aria-label="{{ $aoc->pr->pr_number }} options">
                                    @if ($aoc->is_draft === 1)
                                        <a href="{{ route('aoc.single', ['id' => $aoc->id]) }}" class="btn btn-outline-primary btn-sm"><em class="bi bi-eye-fill"></em></a>
                                    @else
                                        <a target="_blank" href="{{ route('aoc.print', ['id' => $aoc->bac_reso->id]) }}" class="btn btn-outline-primary btn-sm"><em class="bi bi-printer-fill"></em></a>
                                    @endif
                                    <button class="btn btn-outline-danger btn-sm" type="submit">
                                        <em class="bi bi-trash"></em>
                                    </button>
                                </div>
                            </form>
                        </td>
                        <td><span class="badge bg-{{ $aoc->is_draft ? "secondary" : "primary" }}">{{ $aoc->is_draft ? "Draft" : "Done" }}</span> {{ $aoc->pr->pr_number }}</td>
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