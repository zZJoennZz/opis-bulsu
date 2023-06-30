<x-dashboard-layout>
    <x-slot:title>
        Prepare Item Transfer
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => $inventoryItem->transaction->type !== "PAR" ? "All ICS Records" : "All PAR Records", 'route' => $inventoryItem->transaction->type !== "PAR" ? "ics.all" : "ics.all"],
            ['name' => 'Prepare Item Transfer'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    <div class="card mb-3">
        <div class="card-body">
            <div class="text-uppercase small text-muted mb-2">Item Summary</div>
            <div class="row mb-1">
                <div class="col-sm-12 col-md-6">
                    <div class="small text-muted text-uppercase">Item</div>
                    <div class="fw-bold">
                        @php
                            $totalQuantity = $inventoryItem->quantity;
                            foreach($inventoryItem->transfers as $transfer) {
                                $totalQuantity -= $transfer->quantity;
                            }
                        @endphp
                        {{ $inventoryItem->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="small text-muted text-uppercase">Quantity / Unit</div>
                    <div class="fw-bold">
                        {{ $totalQuantity }} {{ $inventoryItem->bac_reso_item->quotation->pr_item->ppmp->item_detail->unit->uom }}
                    </div>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-sm-12 col-md-6">
                    <div class="small text-muted text-uppercase">Unit Price</div>
                    <div class="fw-bold">₱ {{ number_format($inventoryItem->bac_reso_item->quotation->offered_unit_price, 2) }}</div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="small text-muted text-uppercase">Total Price</div>
                    <div class="fw-bold">₱ {{ number_format($totalQuantity * $inventoryItem->bac_reso_item->quotation->offered_unit_price, 2) }}</div>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-sm-12 col-md-6">
                    <div class="small text-muted text-uppercase">Type</div>
                    <div class="fw-bold">
                        @if ($inventoryItem->transaction->type === "PAR")
                            PAR
                        @else
                            ICS
                        @endif
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="small text-muted text-uppercase">Number</div>
                    <span class="badge bg-primary">{{ $inventoryItem->transaction->number }}</span>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-sm-12 col-md-6">
                    <div class="small text-muted text-uppercase">Date Acquired</div>
                    <div class="fw-bold">
                        {{ date('Y-m-d', strtotime($inventoryItem->transaction->date_acquired)) }}
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="small text-muted text-uppercase">Date Issued</div>
                    <div class="fw-bold">
                        {{ date('Y-m-d', strtotime($inventoryItem->transaction->date_issued)) }}
                    </div>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-sm-12 col-md-6">
                    <div class="small text-muted text-uppercase">PTR Date</div>
                    <div class="fw-bold">
                        {{ date('Y-m-d') }}
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="small text-muted text-uppercase">College/Office</div>
                    <div class="fw-bold">
                        {{ $inventoryItem->transaction->branch->branch_name }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="fw-bold text-uppercase">Property Transfer</div>
    <form action="{{ route('transfer.submit') }}/{{ $inventoryItem->id }}" method="post">
        @csrf
        <div class="row mb-3 py-1">
            <div class="col-5">
                <div><label for="issuer" class="form-label">Issuer:</label></div>
                <div>
                    <select class="form-select" id="issuer" name="issuer" aria-label="Select issuer">
                        @foreach ($inventoryItem->transaction->receivers as $receiver)
                        <option value="{{ $receiver->supply_end_users_id }}">{{ $receiver->end_user->first_name }} {{ $receiver->end_user->middle_name }} {{ $receiver->end_user->last_name }}</option>
                        @endforeach
                      </select>
                </div>
            </div>
            <div class="col-2 text-center fs-3 m-auto">
                <em class="bi bi-arrow-right"></em>
            </div>
            <div class="col-5">
                <div><label for="" class="form-label">Receiver:</label></div>
                <div>
                    <select id="receiver" name="receiver" class="form-select" aria-label="Select receiver">
                        @foreach ($supplyEndUsers as $endUser)
                            @if (!in_array($endUser->id, $inventoryItem->transaction->receivers->pluck('supply_end_users_id')->toArray()))
                                <option value="{{ $endUser->id }}">{{ $endUser->first_name }} {{ $endUser->middle_name }} {{ $endUser->last_name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-12 col-md-6">
                <label for="quantity" class="form-label">Quantity to transfer <small class="text-muted">Max quantity allowed: {{ $inventoryItem->quantity }}</small></label>
                <input type="number" class="form-control" value="1" id="quantity" name="quantity" min="1" max="{{ $inventoryItem->quantity }}" required>
            </div>
            <div class="col-sm-12 col-md-6">
                <label for="serialNumbers" class="form-label">Serial number/s to transfer <small class="text-muted">Shift + Click to select multiple</small></label>
                <select class="form-select" id="serialNumbers" name="serialNumbers[]" multiple aria-label="Serial numbers">
                    @foreach ($inventoryItem->serial_numbers as $serialNumber)
                        @if ($serialNumber->current_end_user === null)
                        <option value="{{ $serialNumber->id }}">{{ $serialNumber->serial_number }}</option>
                        @endif                    
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                <label for="reason" class="form-label">Reason for transfer</label>
                <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary">Transfer <em class="bi bi-caret-right"></em></button>
            </div>
        </div>
    </form>
    <x-slot:additional_script>

    </x-slot>
</x-dashboard-layout>