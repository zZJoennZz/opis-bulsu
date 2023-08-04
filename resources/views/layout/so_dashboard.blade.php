<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <h2 class="fw-bold text-uppercase fs-5">Supply Office Dashboard</h2>
</div>
<div class="row">
    <div class="col-sm-12 col-md-9">
        <div class="table-responsive">
            <table class="table table-sm table-hover border-primary caption-top" id="5-recent-transactions">
                <caption class="border border-primary text-primary px-1 py-1 rounded-top">
                    <span class="text-uppercase">5 recent transactions</span> <a href="{{ route('trans.all') }}"
                        class="btn btn-sm btn-outline-primary float-end">View All</a>
                </caption>
                <thead class="text-secondary" style="font-size: 11px;">
                    <tr>
                        <th></th>
                        <th style="width: 50%">Details</th>
                        <th>Date Acquired</th>
                        <th>Date Issued</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lastFiveTransactions as $transaction)
                    <tr>
                        <td>
                            @if ($transaction->type !== "PAR")
                            <a href="{{ route('ics.print', ['id' => $transaction->id]) }}" target="_blank" class="btn btn-sm btn-secondary"><em
                                    class="bi bi-printer-fill"></em></a>
                            @else
                            <a href="{{ route('par.print', ['id' => $transaction->id]) }}" target="_blank" class="btn btn-sm btn-secondary"><em
                                    class="bi bi-printer-fill"></em></a>
                            @endif
                        </td>
                        <td>
                            <div>
                                @if ($transaction->type === "PAR")
                                <span class="badge bg-success">PAR</span>
                                @endif
                                @if ($transaction->type === "ICSL")
                                <span class="badge bg-secondary">ICS <em class="bi bi-caret-down-fill"></em></span>
                                @endif
                                @if ($transaction->type === "ICSH")
                                <span class="badge bg-primary">ICS <em class="bi bi-caret-up-fill"></em></span>
                                @endif
                                <div class="fw-bold">{{ $transaction->number }}</div>
                                <div class="small">
                                    @php
                                    $numOfItems = count($transaction->items);
                                    @endphp
                                    {{ $numOfItems }}
                                    @if ($numOfItems > 1)
                                    items
                                    @else
                                    item
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $transaction->date_acquired }}</td>
                        <td>{{ $transaction->date_issued }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-sm-12 col-md-3">
        <div class="text-primary text-uppercase mb-1">Available Items</div>
        <div>
            <div class="text-center fw-bold text-primary" style="font-size: 100px;">20</div>
            <div class="text-center"><a href="{{ route('ics.all') }}" class="btn btn-sm btn-link">View ICS Items</a></div>
            <div class="text-center"><a href="{{ route('par.all') }}" class="btn btn-sm btn-link">View PAR Items</a></div>
        </div>
    </div>
</div>