<x-dashboard-layout>
    <x-slot:title>
        Disposal Form
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Item Maintenance', 'route' => 'maintenance.index'],
            ['name' => 'Form Type', 'url' => route('maintenance.select', ['id' => $property->id])],
            ['name' => 'Disposal Form'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />

    <div class="card mb-3">
        <div class="card-body">
            <div class="card-title text-uppercase text-secondary fw-bold">Property Information</div>
            <div class="card-text">
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th class="w-25">Item</th>
                            <td>{{ $property->item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}, {{ $property->item->bac_reso_item->quotation->brand_and_model_offered }}</td>
                        </tr>
                        <tr>
                            <th class="w-25">Unit Price</th>
                            <td>₱ {{ number_format($property->item->bac_reso_item->quotation->offered_unit_price, 2) }}</td>
                        </tr>
                        <tr>
                            <th class="w-25">Date Acquired</th>
                            <td>{{ $property->item->transaction->date_acquired }}</td>
                        </tr>
                        <tr>
                            <th class="w-25">Current Owner/Keeper</th>
                            <td>{{ $property->current_owners[0]->end_user->first_name }} {{ $property->current_owners[0]->end_user->middle_name }} {{ $property->current_owners[0]->end_user->last_name }} / <span class="small text-muted">{{ $property->current_owners[0]->end_user->position->name }} - {{ $property->current_owners[0]->end_user->branch->branch_name }}</span class="small text-muted"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <form method="POST" id="maintenance-form" action="{{ route('disposal.formsubmit', ['id' => $property->id]) }}">
        @csrf
        <div class="mb-3">
            <div class="form-floating">
                <textarea class="form-control" placeholder="Enter the cause or damage of property" id="cause_damage" name="cause_damage" style="height: 100px"></textarea>
                <label for="cause_damage">Cause or damage of property</label>
            </div>
        </div>
        <div class="mb-3">
            <div class="form-floating">
                <textarea class="form-control" placeholder="Enter other remarks" id="remarks" name="remarks" style="height: 100px"></textarea>
                <label for="remarks">Other remarks</label>
            </div>
        </div>
        <div>
            <button type="submit" class="btn btn-primary float-end">Submit <em class="bi bi-caret-right-fill"></em></button>
        </div>
    </form>
    <x-slot:additional_script>
        
    </x-slot>
</x-dashboard-layout>