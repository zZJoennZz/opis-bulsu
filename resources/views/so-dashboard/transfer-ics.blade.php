<x-dashboard-layout>
    <x-slot:title>
        Transfer ICS Item
        </x-slot>

        @php
        $breadcrumb = [
        ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
        ['name' => 'All ICS Records', 'route' => 'ics.all'],
        ['name' => 'Transfer ICS Item'],
        ]
        @endphp
        @php
        $quote = $icsItem->bac_reso_item->quotation;
        @endphp
        <x-breadcrumb :breadcrumb="$breadcrumb" />
        <div class="card card-table d-inline float-end ms-2 mb-2">
            <div class="card-body">
                <h5 class="card-title">{{ $icsItem->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}</h5>
                <div class="card-text">
                    <table class="table table-bordered">
                        <caption>Item Information</caption>
                        <tr>
                            <th style="width: 30%;">Brand</th>
                            <td>{{ $quote->brand_and_model_offered }}</td>
                        </tr>
                        <tr>
                            <th>Available Unit/s</th>
                            <td>{{ count($icsItem->properties) }} {{ $icsItem->bac_reso_item->quotation->pr_item->ppmp->item_detail->unit->uom }}</td>
                        </tr>
                        <tr>
                            <th>Unit Price</th>
                            <td>₱ {{ number_format($quote->offered_unit_price, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('transfer_ics.perform') }}/{{ $icsItem->id }}" id="ics-transfer-form">
            @csrf
            <div class="row mt-2 mb-3">
                <div class="col-5 border py-2">
                    <div class="text-uppercase small text-muted">Issuer</div>
                    <div>
                        <div>{{ $icsItem->transaction->receivers[0]->end_user->first_name }} {{
                            $icsItem->transaction->receivers[0]->end_user->middle_name }} {{ $icsItem->transaction->receivers[0]->end_user->last_name
                            }}
                        </div>
                        <div class="small text-muted">{{ $icsItem->transaction->receivers[0]->end_user->position->name }}</div>
                        <div class="small text-muted">{{ $icsItem->transaction->receivers[0]->end_user->branch->branch_name }}</div>
                    </div>
                </div>
                <div class="col-2 fs-2 text-center"><em class="bi bi-arrow-right"></em></div>
                <div class="col-5 border py-2">
                    <label for="receiver" class="text-uppercase small text-muted">Receiver</label>
                    <select class="form-select" id="receiver" name="receiver" aria-label="Default select example">
                        <option selected hidden disabled>Open to select receiver</option>
                        @foreach ($endUsers as $endUser)
                        @if ($icsItem->transaction->receivers[0]->end_user->id !== $endUser->id)
                        <option value="{{ $endUser->id }}">{{ $endUser->first_name }} {{ $endUser->middle_name }} {{ $endUser->last_name }} ({{
                            $endUser->position->name }} / {{ $endUser->branch->branch_name }})</option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input required type="number" class="form-control" id="quantity" name="quantity" min="1" max="{{ count($icsItem->properties) }}">
                </div>
            </div>
            @if ($icsItem->properties[0]->serial_number !== "n/a")
            <div class="row mb-3">
                <div class="col-12">
                    <div class="form-label">Select Unit/s to Transfer</div>
                    @foreach ($icsItem->properties as $property)
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="serial_number_{{ $property->id }}" name="serial_numbers"
                            value="{{ $property->id }}">
                        <label class="form-check-label" for="serial_number_{{ $property->id }}">{{ $property->serial_number }}</label>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            <div class="row mb-3">
                <div class="col-12">
                    <label for="reason" class="form-label">Reason for transfer</label>
                </div>
                <div class="col-12">
                    <textarea class="form-control" name="reason" id="reason" rows="5" required></textarea>
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Submit</button>
        </form>
        <x-slot:additional_script>
            <script defer>
                let frm = document.querySelector('#ics-transfer-form');
                frm.addEventListener('submit', function(event) {
                    const e = event || window.event;
                    e.preventDefault();
                    const checkedCheckboxes = document.querySelectorAll('input[name="serial_numbers"]:checked');
                    const selectedSerialNumbers = Array.from(checkedCheckboxes).map(checkbox => checkbox.value);
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'selected_serial_numbers';
                    hiddenInput.value = JSON.stringify(selectedSerialNumbers);
                    this.appendChild(hiddenInput);
                    this.submit();
                });
            </script>
            <style>
                .card-table {
                    width: 500px;
                }

                @media screen and (max-width: 768px) {
                    .card-table {
                        width: 100%;
                    }
                }
            </style>
            </x-slot>
</x-dashboard-layout>