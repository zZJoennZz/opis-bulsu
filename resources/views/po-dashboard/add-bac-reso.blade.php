<x-dashboard-layout>
    <x-slot:title>
        Generate BAC Resolution
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'BAC Resolution <span class="badge bg-primary">' . getPpmpYear() . '</span>', 'route' => 'bac-reso.all'],
            ['name' => 'Generate']
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    
    <form action="{{ route('bac-reso.save') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="purchase_request" class="form-label">Purchase Request</label>
            <select name="purchase_request" id="purchase_request" class="form-select" aria-label="Select purchase request">
                <option value="0" disabled selected>Select purchase request</option>
                @foreach ($purchase_requests as $pr)
                    <option value="{{ $pr->id }}">{{ $pr->pr->pr_number }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="type" class="form-label">BAC Resolution Type</label>
            <select name="type" id="type" class="form-select" aria-label="Select BAC resolution type">
                <option value="0" disabled selected>Select BAC resolution type</option>
                <option value="BY_LOT">By lot</option>
                <option value="BY_ITEM">By item</option>
            </select>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary">
                Generate <em class="bi bi-chevron-double-right"></em>
            </button>
        </div>

    </form>
    
    <x-slot:additional_script>
        
    </x-slot>
</x-dashboard-layout>