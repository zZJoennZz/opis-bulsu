<x-dashboard-layout>
    <x-slot:title>
        Add Abstract of Canvass
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>'],
            ['name' => 'Abstract of Canvasses <span class="badge bg-primary">'. Auth::user()->ppmp_year .'</span>', 'route' => 'aoc.all'],
            ['name' => 'Generate'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    
    <div class="row">
        <div class="col-12"><label for="purchase_requests_id" class="form-label">Purchase Request</label></div>
    </div>
    <div class="row mb-3">
        <div class="col-sm-12 col-md-10">
            <select class="form-select" aria-label="Select purchase request">
                <option disabled selected>Select purchase request</option>
                @foreach ($pr_without_abstract as $pr)
                    <option value="{{ $pr->id }}">{{ $pr->pr_number }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-12 col-md-2 p-1 text-center"><a href="#" class="btn btn-success btn-sm"><em class="bi bi-eye-fill"></em> View Items</a></div>
    </div>
    <div class="row">
        <div class="col-12">
            <label for="purpose" class="form-label">Purpose</label>
            <input type="text" class="form-control" id="purpose" name="purpose">
        </div>
    </div>

    <x-slot:additional_script>
        
    </x-slot>
</x-dashboard-layout>