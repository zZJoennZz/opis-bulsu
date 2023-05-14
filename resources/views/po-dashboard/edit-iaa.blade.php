<x-dashboard-layout>
    <x-slot:title>
        Inspection and Acceptance Report
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Inspection and Acceptance Report', 'route' => 'ia.all'],
            ['name' => 'Complete Form']
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    
    <form action="{{ route('iaa.put') }}/{{ $iaa->id }}" method="POST">
        @csrf
        @method("PUT")
        <div class="mb-3">
            <label for="iar_number" class="form-label">I.A.R. No.:</label>
            <input type="text" class="form-control" id="iar_number" name="iar_number" placeholder="Please enter inspection and acceptance report number here." value="{{ $iaa->iar_no }}">
        </div>
        <div class="mb-3">
            <label for="iar_date" class="form-label">I.A.R. Date:</label>
            <input type="date" class="form-control" id="iar_date" name="iar_date" value="{{ $iaa->iar_date }}">
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-primary">
                Complete <em class="bi bi-chevron-double-right"></em>
            </button>
        </div>

    </form>
    
    <x-slot:additional_script>
        
    </x-slot>
</x-dashboard-layout>