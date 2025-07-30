<x-dashboard-layout>
    <x-slot:title>
        Disposal Form
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Item Maintenance', 'route' => 'maintenance.index'],
            ['name' => 'Form Type', 'url' => route('maintenance.select')],
            ['name' => 'Disposal Form'],
        ]
    @endphp 

    <x-breadcrumb :breadcrumb="$breadcrumb" />

    <div class="text-lg mb-3">Please complete the form/s</div>
    <form method="POST" id="maintenance-form" action="{{ route('disposal.formsubmit') }}">
        @csrf
        @foreach ($properties as $property)
            <div class="card mb-3 border-primary">
                <div class="card-body">
                    <div class="card-title mb-3">
                        {{ $property->item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description . ', ' . $property->item->bac_reso_item->quotation->brand_and_model_offered . ', S/N: ' . $property->serial_number ?? "n/a" }}
                    </div>
                    <input type="hidden" name="itemId[]" value="{{ $property->id }}">
                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Enter the cause or damage of property" id="cause_damage{{ $property->id }}" name="cause_damage[]" style="height: 100px"></textarea>
                            <label for="cause_damage{{ $property->id }}">Cause or damage of property</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Enter the property condition" id="property_condition{{ $property->id }}" name="property_condition[]" style="height: 100px"></textarea>
                            <label for="property_condition{{ $property->id }}">Property condition</label>
                        </div>
                    </div>
                    <div>
                        <div class="form-floating">
                            <textarea class="form-control" placeholder="Enter other remarks" id="remarks{{ $property->id }}" name="remarks[]" style="height: 100px"></textarea>
                            <label for="remarks{{ $property->id }}">Other remarks</label>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="mb-3 row">
            <div class="col-md-6 col-sm-12">
                <div class="mb-3">
                    <label class="form-label" for="verifier">Checked & Verified as to the Record of Accountability</label>
                    <input type="text" class="form-control" id="verifier" name="verifier">
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="mb-3">
                    <label class="form-label" for="noted_by">Noted by:</label>
                    <input value="{{ getSettingValue('head_asset_management_unit') }}" type="text" class="form-control" id="noted_by" name="noted_by">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="designation">Designation:</label>
                    <input value="Head, Asset Management Unit" type="text" class="form-control" id="designation" name="designation">
                </div>
            </div>
        </div>
        <div>
            <button type="submit" class="btn btn-primary float-end">Submit <em class="bi bi-caret-right-fill"></em></button>
        </div>
    </form>
    <x-slot:additional_script>
        
    </x-slot>
</x-dashboard-layout>