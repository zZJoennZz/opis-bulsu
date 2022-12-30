@include('layout/header', ['title' => 'Approved PPMP | OPIS - BulSU e-PROCUREMENT'])
@include('layout/member_header')
<div class="container-fluid">
    <div class="row">
        @include('layout/sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="pt-3">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-4">
                            <a href="{{ route('po-dashboard.show') }}" class="btn btn-secondary"><em class="bi bi-arrow-bar-left"></em> Back</a>
                        </div>
                        <hr />
                        <div class="mb-3 fs-3">
                            CAMPUS / OFFICE: <strong>{{ $branch->branch_name }}</strong> <span class="badge bg-primary">{{ $record_year }}</span>
                        </div>
                        <table class="table table-small table-bordered" id="ppmp-record">
                            <thead>
                                <tr>
                                    <th scope="col" rowspan="2">Item</th>
                                    <th scope="col" rowspan="2">Unit of Measurement</th>
                                    <th scope="col" rowspan="2">Estimated Budget</th>
                                    <th scope="col" colspan="{{ count(json_decode($ppmp_format->format)) }}">Milestone of Activities</th>
                                    <th scope="col" rowspan="2">Total Qty</th>
                                    <th scope="col" rowspan="2">Price Catalogue</th>
                                    <th scope="col" rowspan="2">Total Amount</th>
                                    <th scope="col" rowspan="2">Remarks</th>
                                </tr>
                                <tr>
                                    @foreach (json_decode($ppmp_format->format) as $format)
                                    <th id="{{ $format->id }}">{{ $format->name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @php($totalAmount = 0)
                                @foreach($ppmp_record as $record)
                                <td>{{ $record->item_detail->description }}</td>
                                <td>{{ $record->item_detail->unit->uom }}</td>
                                <td>₱{{ number_format($record->estimated_budget, 2) }}</td>
                                @php(
                                    $qty = 0
                                    )
                                    @foreach ($record->milestones as $item)
                                    @php (
                                        $qty += $item->milestone_value
                                        )
                                        <td>{{ $item->milestone_value }}</td>
                                        @endforeach
                                    <td>{{ $qty }}</td>
                                    <td>₱{{ $record->item_detail->price_catalogue }}</td>
                                    @php($totalAmount += floatval($record->item_detail->price_catalogue) * floatval($qty))
                                    <td>₱{{ number_format(floatval($record->item_detail->price_catalogue) * floatval($qty), 2) }}</td>
                                    <td>{{ $record->remarks }}</td>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td class="fs-3 fw-bold text-end" colspan="{{ count(json_decode($ppmp_format->format)) + 4 }}">TOTAL AMOUNT</td>
                                    <td class="fs-3 fw-bold text-start" colspan="2">₱{{ number_format($totalAmount, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
@include('layout/datatable', ['tableId' => 'ppmp-record'])
@include('layout/footer')