@include('layout/header', ['title' => 'New PPMP Budget Request | OPIS - BulSU e-PROCUREMENT'])
@include('layout/member_header')
<div class="container-fluid">
    <div class="row">
        @include('layout/sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger mt-3 mb-3" role="alert">
                        {{$error}}
                    </div>
                @endforeach
            @endif
            <div class="mt-4">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive mb-3">
                            <table class="table table-sm table-bordered border-dark caption-top" id="approved-ppmp-table">
                                <caption>Approved Project Procurement Management Plan Requests <span class="badge text-bg-primary">Year <strong>{{ Auth::user()->ppmp_year }}</strong></span></caption>
                                <thead class="text-center">
                                    <tr>
                                        <th rowspan="2" scope="col">Item Description</th>
                                        <th rowspan="2" scope="col">Unit of Measurement</th>
                                        <th rowspan="2" scope="col">Estimated Budget</th>
                                        <th colspan="{{ count($ppmp_format) }}" scope="col">Schedule/Milestone of Activities</th>
                                        <th rowspan="2" scope="col">Total Qty</th>
                                        <th rowspan="2" scope="col">Price Catalogue</th>
                                        <th rowspan="2" scope="col">Total Amount</th>
                                        <th rowspan="2" scope="col">Remarks</th>
                                    </tr>
                                    <tr>
                                        @foreach ($ppmp_format as $format)
                                            <th id="{{ $format->id }}">{{ $format->name }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php ($totalTotalAmount = 0)
                                    @foreach ($ppmp_items as $item)
                                        @php ($totalAmount = 0)
                                        @php ($totalQty = 0)
                                        <tr>
                                            <td>
                                                {{ $item->description }}
                                                <div>
                                                    @if ($item->is_bo_approve === 1)
                                                        <span class="badge text-bg-primary"><em class="bi bi-check-circle-fill"></em> Budget Office</span>
                                                    @endif
                                                    @if ($item->is_pr_approve === 1)
                                                        <span class="badge text-bg-primary"><em class="bi bi-check-circle-fill"></em> Procurement Unit</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>{{ $item->uom }}</td>
                                            <td>₱{{ number_format($item->estimated_budget, 2) }}</td>
                                            @foreach ($milestones as $milestone)
                                                @if ($milestone->pro_pro_man_plans_id === $item->id)
                                                    @php ($totalQty += $milestone->milestone_value)
                                                    <td>{{ $milestone->milestone_value }}</td>
                                                @endif
                                            @endforeach
                                            <td>{{ $totalQty }}</td>
                                            <td>₱{{ number_format($item->price_catalogue, 2) }}</td>
                                            @php ($totalAmount = $totalQty * $item->price_catalogue)
                                            <td>₱{{ number_format($totalAmount, 2) }}</td>
                                            @php ($totalTotalAmount = floatval($totalTotalAmount) + floatval($totalAmount))
                                            <td>{{ $item->remarks }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td colspan="{{ count($ppmp_format) + 4 }}" class="fs-3 text-uppercase text-end">
                                            <strong>Total Amount</strong>
                                        </td>
                                        <td colspan="2" class="fs-3 text-uppercase text-start">
                                            ₱{{ number_format($totalTotalAmount, 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
@include('layout/datatable', ["tableId" => "approved-ppmp-table"])
@include('layout/footer')