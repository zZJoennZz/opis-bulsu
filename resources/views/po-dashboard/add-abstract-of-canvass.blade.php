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

    <form action="{{ route('aoc.perform') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-12"><label for="purchase_requests_id" class="form-label">Purchase Request</label></div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-12 col-md-10">
                <select class="form-select" id="purchase_requests_id" name="purchase_requests_id" aria-label="Select purchase request" required>
                    <option disabled selected>Select purchase request</option>
                    @foreach ($pr_without_abstract as $pr)
                        <option value="{{ $pr->id }}">{{ $pr->pr_number }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-12 col-md-2 p-1 text-center"><a href="#" class="btn btn-success btn-sm"><em class="bi bi-eye-fill"></em> View Items</a></div>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                <label for="purpose" class="form-label">Purpose</label>
                <input type="text" class="form-control" id="purpose" name="purpose" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                <label for="abc" class="form-label">ABC</label>
                <input type="text" class="form-control" id="abc" name="abc" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-12 col-md-6 col-md-6">
                <label for="bac_chairman" class="form-label">BAC Chairman</label>
                <input type="text" class="form-control" id="bac_chairman" name="bac_chairman" value="{{ getSettingValue("bac_chairman") }}" required>
            </div>
            <div class="col-sm-12 col-md-6 col-md-6">
                <label for="vice_chairman" class="form-label">Vice Chairman</label>
                <input type="text" class="form-control" id="vice_chairman" name="vice_chairman" value="{{ getSettingValue("vice_chair_1") }}" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-12 col-md-6 col-md-6">
                <label for="member_1" class="form-label">Member</label>
                <input type="text" class="form-control" id="member_1" name="member_1" value="{{ getSettingValue("member_1") }}" required>
            </div>
            <div class="col-sm-12 col-md-6 col-md-6">
                <label for="member_2" class="form-label">Member</label>
                <input type="text" class="form-control" id="member_2" name="member_2" value="{{ getSettingValue("member_2") }}" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-12 col-md-6 col-md-6">
                <label for="member_3" class="form-label">Member</label>
                <input type="text" class="form-control" id="member_3" name="member_3" value="{{ getSettingValue("member_3") }}" required>
            </div>
            <div class="col-sm-12 col-md-6 col-md-6">
                <label for="member_4" class="form-label">Member</label>
                <input type="text" class="form-control" id="member_4" name="member_4" value="{{ getSettingValue("member_4") }}" required>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-end">
                <button class="btn btn-primary" type="submit"><em class="bi bi-save2"></em> Save</button>
            </div>
        </div>
    </form>

    <x-slot:additional_script>
        
    </x-slot>
</x-dashboard-layout>