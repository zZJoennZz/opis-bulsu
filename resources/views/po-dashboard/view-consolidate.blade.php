@include('layout/header', ['title' => 'Consolidate | OPIS - BulSU e-PROCUREMENT'])
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
                            <span class="float-end small"># of records: <span class="badge text-bg-secondary">{{ count($consolidated_records) }}</span></span>
                        </div>
                        <hr />
                        @if ($consolidated_records === null || count($consolidated_records) === 0)
                            <p class="text-center fst-italic text-secondary">PPMP for the year <span class="badge bg-primary">{{ Auth::user()->ppmp_year }}</span> is not yet consolidated.</p>
                            <form onsubmit="return confirm(`Are you sure to consolidate the submitted PPMP as of ${new Date().toLocaleDateString('en-PH', { weekday: 'short', 'year': 'numeric', 'day': 'numeric', 'month': 'long', 'hour': '2-digit', 'minute': '2-digit', 'second': '2-digit' })} for the year {{ Auth::user()->ppmp_year }}.`)" class="d-flex w-100" method="POST" action="{{ route('consolidate.perform') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-lg mx-auto"><em class="bi bi-collection-fill"></em> Consolidate <span class="badge bg-dark">{{ Auth::user()->ppmp_year }}</span></button>
                            </form>
                        @else
                            <form onsubmit="return confirm('Are you sure to reset the consolidated records?')" action="{{ route('consolidate.reset') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-lg mx-auto"><em class="bi bi-arrow-clockwise"></em> Reset</button>
                            </form>
                            <div class="table-responsive mt-3">
                                <table class="table table-small table-bordered border-dark caption-top" id="consolidated-ppmp">
                                    <caption class="fs-4 fw-bold text-uppercase">Consolidated Annual Procurement Plan <span class="badge text-bg-primary">{{ Auth::user()->ppmp_year }}</span></caption>
                                    <thead>
                                        <tr>
                                            <th style="width: 45%;">Item Detail</th>
                                            <th class="text-center" style="width: 10%;">Unit</th>
                                            <th class="text-center" style="width: 15%;">Qty</th>
                                            <th class="text-center" style="width: 15%;">Price Catalogue</th>
                                            <th class="text-center" style="width: 15%;">Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php($grandTotalAmt = 0)
                                        @php($grandTotalQty = 0)
                                        @php($grandPriceCat = 0)
                                        @foreach ($consolidated_records as $record => $ppmp)
                                            <tr>
                                                <td>{{ json_decode($record)->description }}</td>
                                                <td>{{ $ppmp[0]->item_detail->unit->uom }}</td>
                                                <td class="text-center">
                                                    @php($totalQty = 0)
        
                                                    @foreach ($ppmp[0]->milestones as $rec)
                                                        @php($totalQty += $rec->milestone_value)
                                                    @endforeach
        
                                                    {{ $totalQty }}

                                                    @php($grandTotalQty += $totalQty)
                                                </td>
                                                <td class="text-end">
                                                    <div class="float-start">₱</div>
                                                    {{ number_format(json_decode($record)->price_catalogue, 2) }}
                                                    @php($grandPriceCat += json_decode($record)->price_catalogue)
                                                </td>
                                                <td class="text-end">
                                                    <div class="float-start">₱</div>
                                                    @php($totalAmount = floatval($totalQty) * floatval(json_decode($record)->price_catalogue))
                                                    {{ number_format($totalAmount, 2) }}

                                                    @php($grandTotalAmt += floatval($totalAmount) )
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="fs-4 fw-bold">
                                            <td class="text-end" colspan="2">Grand Total</td>
                                            <td class="text-center">{{ $grandTotalQty }}</td>
                                            <td class="text-end">
                                                <div class="float-start">₱</div>
                                                {{ number_format($grandPriceCat, 2) }}
                                            </td>
                                            <td class="text-end">
                                                <div class="float-start">₱</div>
                                                {{ number_format($grandTotalAmt, 2) }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
@if ($consolidated_records !== null || count($consolidated_records) !== 0)
@include('layout/datatable', ['tableId' => 'consolidated-ppmp'])
@endif
@include('layout/footer')