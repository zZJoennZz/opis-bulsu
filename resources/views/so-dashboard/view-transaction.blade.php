<x-dashboard-layout>
    <x-slot:title>
        View Transaction No. {{ $transaction->number }}
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'All Transactions', 'route' => 'trans.all'],
            ['name' => 'View Transaction'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />

    
    <div class="row">
        <div class="col-12 text-end">
            @if($transaction->type === "PAR")
            <a href="{{ route('par.print', ['id' => $transaction->id]) }}" target="_blank" class="btn btn-secondary"><em
                class="bi bi-printer-fill"></em></a>
            @else
            <a href="{{ route('ics.print', ['id' => $transaction->id]) }}" target="_blank" class="btn btn-secondary"><em
                class="bi bi-printer-fill"></em></a>
            @endif
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12 mb-3">
            @if($transaction->type === "PAR")
            <span class="badge bg-success">PAR</span>
            @endif
            @if($transaction->type === "ICSL")
            <span class="badge bg-secondary">ICS <em class="bi bi-caret-down-fill"></em></span>
            @endif
            @if($transaction->type === "ICSH")
            <span class="badge bg-secondary">ICS <em class="bi bi-caret-up-fill"></em></span>
            @endif
        </div>
        <div class="col-12 mb-3">
            <div>
                <label for="number" class="form-label">
                    @if ($transaction->type === "PAR")
                    PAR No.
                    @else
                    ICS No.
                    @endif
                </label>
                <input type="text" class="form-control" value="{{ $transaction->number }}" readonly>
            </div>
        </div>
        <div class="col-12 mb-3">
            <div>
                <label for="purchase_number" class="form-label">Purchase Order No.:</label>
                <input type="text" class="form-control" value="{{ $transaction->purchase_order->po_number }}" readonly>
            </div>
        </div>
        <div class="col-12 mb-3">
            <div>
                <label for="branch" class="form-label">Branch:</label>
                <select class="form-select" name="branch" id="branch" aria-label="Default select example">
                    @foreach ($branches as $branch)
                        @if ($branch->type !== "DEVELOPER")
                            @if ($branch->id === $transaction->branches_id)
                            <option selected value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                            @else
                            <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                            @endif
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-6 mb-3">
            <div>
                <label for="date_acquired" class="form-label">Date Acquired</label>
                <input type="date" class="form-control" value="{{ $transaction->date_acquired }}" name="date_acquired" id="date_acquired">
            </div>
        </div>
        <div class="col-6">
            <div>
                <label for="date_issued" class="form-label">Date Issued</label>
                <input type="date" class="form-control" value="{{ $transaction->date_issued }}" name="date_issued" id="date_issued">
            </div>
        </div>
        <div class="col-6 mb-3">
            <div>
                <label for="issuer" class="form-label">Issuer</label>
                <input type="text" class="form-control" value="{{ $transaction->issuers[0]->employee->first_name . ' ' . $transaction->issuers[0]->employee->middle_name . ' ' . $transaction->issuers[0]->employee->last_name }}" id="issuer" readonly>
            </div>
        </div>
        <div class="col-6">
            <div>
                <label for="receiver" class="form-label">Receiver</label>
                <input type="text" class="form-control" value="{{ $transaction->receivers[0]->end_user->first_name . ' ' . $transaction->receivers[0]->end_user->middle_name . ' ' . $transaction->receivers[0]->end_user->last_name }}" id="receiver" readonly>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <table class="w-100 caption-top">
                @if ($transaction->type === "PAR")
                <caption>PAR Item/s</caption>
                @else
                <caption>ICS Item/s</caption>
                @endif
                <thead class="align-middle text-center">
                    <tr>
                        <th style="width: 10%; border: 1px solid #000;">Quantity</th>
                        <th style="width: 10%; border: 1px solid #000;">Unit</th>
                        <th style="width: 30%; border: 1px solid #000;">Description</th>
                        <th style="width: 15%; border: 1px solid #000;">Property No.</th>
                        <th style="width: 15%; border: 1px solid #000;">Date Acquired</th>
                        <th style="width: 20%; border: 1px solid #000;">Amount</th>
                    </tr>
                </thead>
                <tbody class="text-center"
                    style="border-left: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000; border-top: 1px solid #000;">

                    @foreach ($transaction->items as $item)
                    <tr style="border-bottom: none !important;">
                        <td style="border-bottom: 0px solid #fff !important;">{{ $item->quantity }}</td>
                        <td>{{ $item->bac_reso_item->quotation->pr_item->ppmp->item_detail->unit->uom }}</td>
                        <td>{{ $item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}, {{ $item->bac_reso_item->quotation->brand_and_model_offered }}</td>
                        <td>{{ $item->property_number }}</td>
                        <td>₱ {{ number_format($item->bac_reso_item->quotation->offered_unit_price, 2) }}</td>
                        <td>₱ {{ number_format($item->bac_reso_item->quotation->offered_unit_price * $item->quantity, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div>
                File Attachments
            </div>
            <div class="mb-3">
                @if ($transaction->file_attachments_id === null)
                    <span class="small text-secondary fst-italic">
                        No attachment for this transaction.
                    </span>
                @else
                <ul>
                    @foreach (json_decode($transaction->file_attachments_id) as $item)
                    @php
                        $attachmentName = getFileAttachment($item);
                    @endphp
                    <li class="mb-2">
                        <a href="{{ asset('storage/attachments/' .  $attachmentName) }}" target="_blank" rel="noopener noreferrer">{{ $attachmentName }}</a>
                        <form method="POST" action="{{ route('tran.delete_attachment', ['itemId' => $item, 'tranId' => $transaction->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>
            <div class="row">
                <div class="col-12">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('tran.attach', ['id' => $transaction->id]) }}">
                        @csrf
                        <input class="form-control w-25" type="file" id="file_attachment" name="file_attachment">
                        <div class="mt-1 small muted">PDF, JPG, or PNG ONLY!</div>
                        <button class="btn btn-primary small mt-1" type="submit">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-slot:additional_script>

    </x-slot>
</x-dashboard-layout>