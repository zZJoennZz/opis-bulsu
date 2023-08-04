<x-dashboard-layout>
    <x-slot:title>
        {{ $item->item->bac_reso_item->quotation->brand_and_model_offered . " / " .
        $item->item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}
        </x-slot>

        @php
        $breadcrumb = [
        ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
        ['name' => 'Properties', 'route' => 'transfered_items.all'],
        ['name' => $item->item->bac_reso_item->quotation->brand_and_model_offered . " / " .
        $item->item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description],
        ]
        @endphp

        <x-breadcrumb :breadcrumb="$breadcrumb" />
        <div class="mb-3">
            <div class="fs-4 mb-2">{{ $item->item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}</div>
            <div class="text-muted small fst-italic mb-2"><strong>Brand:</strong>
                <div>{{ $item->item->bac_reso_item->quotation->brand_and_model_offered }}</div>
            </div>
            <div class="text-muted small fst-italic"><strong>Current Keeper:</strong>
                @foreach ($item->current_owners as $keeper)
                <div class="mb-1">
                    <span class="badge bg-primary fs-6">{{ $keeper->end_user->first_name }} {{ $keeper->end_user->middle_name }} {{
                        $keeper->end_user->last_name }}</span>
                </div>
                <div class="mb-1">
                    <span>{{ $keeper->end_user->position->name }} / {{ $keeper->end_user->branch->branch_name }}</span>
                </div>
                @endforeach
            </div>
        </div>
        <hr />
        <div class="mb-3">
            <div class="text-uppercase small text-muted mb-2 border-bottom pb-2">Keeper History</div>
            @foreach ($item->transfers as $transfer)
            <div class="border-bottom mb-2 pb-2">
                @foreach ($transfer->transfer->receivers as $receiver)
                @if (in_array($receiver->end_user->id, $item->current_owners->pluck('supply_end_users_id')->toArray()))
                <div class="text-primary">
                    <div class="float-end">{{ date('Y-m-d', strtotime($transfer->created_at)) }}</div>
                    <span class="badge bg-primary">Current</span> {{ $receiver->end_user->first_name }} {{
                    $receiver->end_user->middle_name }} {{
                    $receiver->end_user->last_name }}
                </div>
                @else
                <div class="text-muted">{{ $receiver->end_user->first_name }} {{ $receiver->end_user->middle_name }} {{
                    $receiver->end_user->last_name }}</div>
                @endif
                @endforeach
            </div>
            @endforeach
            <div class="border-bottom pb-2">
                <div class="float-end text-muted">{{ $item->item->transaction->date_acquired }}</div>
                @foreach ($item->item->transaction->receivers as $receiver)
                <div class="text-muted">
                    {{ $receiver->end_user->first_name }} {{ $receiver->end_user->middle_name }} {{
                    $receiver->end_user->last_name }}
                </div>
                @endforeach
            </div>
        </div>
        <x-slot:additional_script>

            </x-slot>
</x-dashboard-layout>