<x-dashboard-layout>
    <x-slot:title>
        Transfer Item
        </x-slot>

        @php
        $breadcrumb = [
        ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
        ['name' => 'Properties', 'route' => 'transfered_items.all'],
        ['name' => 'Transfer Item'],
        ]
        @endphp
        <x-breadcrumb :breadcrumb="$breadcrumb" />
        <div class="card card-table d-inline float-end ms-2 mb-2">
            <div class="card-body">
                <h5 class="card-title">{{ $item->item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}</h5>
                <div class="card-text">
                    <table class="table table-bordered">
                        <caption>Item Information</caption>
                        <tr>
                            <th style="width: 30%;">Brand</th>
                            <td>{{ $item->item->bac_reso_item->quotation->brand_and_model_offered }}</td>
                        </tr>
                        <tr>
                            <th>Unit Price</th>
                            <td>₱ {{ number_format($item->item->bac_reso_item->quotation->offered_unit_price, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('user.transfer.perform', ['propertyId' => $item->id]) }}" id="ics-transfer-form">
            @csrf
            <div class="row mt-2 mb-3">
                <div class="col-5 border py-2">
                    <div class="text-uppercase small text-muted">Issuer</div>
                    <div>
                        <div>{{ $item->current_owners[0]->end_user->first_name }} {{
                            $item->current_owners[0]->end_user->middle_name }} {{
                            $item->current_owners[0]->end_user->last_name
                            }}
                        </div>
                        <div class="small text-muted">{{ $item->current_owners[0]->end_user->position->name }}</div>
                        <div class="small text-muted">{{ $item->current_owners[0]->end_user->branch->branch_name }}</div>
                    </div>
                </div>
                <div class="col-2 fs-2 text-center"><em class="bi bi-arrow-right"></em></div>
                <div class="col-5 border py-2">
                    <label for="receiver" class="text-uppercase small text-muted">Receiver</label>
                    <select class="form-select" id="receiver" name="receiver" aria-label="Default select receiver">
                        <option selected hidden disabled>Open to select receiver</option>
                        @foreach ($endUsers as $endUser)
                        <option value="{{ $endUser->id }}">{{ $endUser->first_name }} {{ $endUser->middle_name }} {{ $endUser->last_name }} ({{
                            $endUser->position->name }} / {{ $endUser->branch->branch_name }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
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