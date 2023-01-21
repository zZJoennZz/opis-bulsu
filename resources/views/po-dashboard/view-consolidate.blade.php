@include('layout/header', ['title' => 'Consolidate | OPIS - BulSU e-PROCUREMENT'])
@include('layout/member_header')
<div class="container-fluid">
    <div class="row">
        @include('layout/sidebar')
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="pt-3">
                <div class="card">
                    <div class="card-body">
                        @include('layout/breadcrumb',
                        [
                            'breadcrumbs' => [
                                ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
                                ['name' => 'Consolidated Annual Procurement Plan <span class="badge bg-primary">' . Auth::user()->ppmp_year . '</span>']
                            ]
                        ]
                        )
                        @if ($consolidated_records === null || count($consolidated_records) === 0)
                            <p class="text-center fst-italic text-secondary">PPMP for the year <span class="badge bg-primary">{{ Auth::user()->ppmp_year }}</span> is not yet consolidated.</p>
                            <form onsubmit="return confirm(`Are you sure to consolidate the submitted PPMP as of ${new Date().toLocaleDateString('en-PH', { weekday: 'short', 'year': 'numeric', 'day': 'numeric', 'month': 'long', 'hour': '2-digit', 'minute': '2-digit', 'second': '2-digit' })} for the year {{ Auth::user()->ppmp_year }}.`)" class="d-flex w-100" method="POST" action="{{ route('consolidate.perform') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-lg mx-auto"><em class="bi bi-collection-fill"></em> Consolidate <span class="badge bg-dark">{{ Auth::user()->ppmp_year }}</span></button>
                            </form>
                        @else
                            <div class="mb-3">
                                <span class="float-end small"># of records: <span class="badge text-bg-secondary">{{ count($consolidated_records) }}</span></span>
                            </div>
                            <form onsubmit="return confirm('Are you sure to reset the consolidated records?')" action="{{ route('consolidate.reset') }}" method="POST">
                                @csrf
                                <div class="btn-group" role="toolbar" aria-label="Consolidated PPMP Records Tools">
                                    <button onclick="window.print()" type="button" class="btn btn-outline-success"><em class="bi bi-printer-fill"></em> Print</button>
                                    <button onclick="printConsolidated()" type="button" class="btn btn-outline-success"><em class="bi bi-file-earmark-pdf-fill"></em> Save as PDF</button>
                                    <button type="submit" class="btn btn-outline-danger"><em class="bi bi-arrow-clockwise"></em> Reset</button>
                                </div>
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
<div id="printReport" class="for-print d-none d-print-none">
    <div class="row mb-3">
        <div class="col-2"></div>
        <div class="col-8 text-center fw-bold">
            <img src="{{ asset('img/bsu-small-logo.png') }}" alt="bsu logo" width="100" style="float: left;" />
            <div class="h-100 d-flex align-content-center flex-column justify-content-center">
                <div>Republic of Philippines</div>
                <div class="fs-5">Bulacan State University</div>
            </div>
        </div>
        <div class="col-2"></div>
    </div>
    <div class="row mb-4">
        <div class="col-12 text-center fs-4 fw-bold">
            Consolidated Annual Procurement Plan {{ Auth::user()->ppmp_year }}
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table table-sm table-bordered border-dark">
                <thead>
                    <tr>
                        <th style="width: 40%; padding: 0.8rem;" class="text-center">Item Detail</th>
                        <th style="width: 10%; padding: 0.8rem;" class="text-center">Unit</th>
                        <th style="width: 10%; padding: 0.8rem;" class="text-center">Quantity</th>
                        <th style="width: 20%; padding: 0.8rem;" class="text-center">Price Catalogue</th>
                        <th style="width: 20%; padding: 0.8rem;" class="text-center">Total Amount</th>
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
            </table>
        </div>
    </div>
</div>
<link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
@if ($consolidated_records !== null || count($consolidated_records) !== 0)
@include('layout/datatable', ['tableId' => 'consolidated-ppmp'])
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js" integrity="sha512-YcsIPGdhPK4P/uRW6/sruonlYj+Q7UHWeKfTAkBW+g83NKM+jMJFJ4iAPfSnVp7BKD4dKMHmVSvICUbE/V1sSw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script defer>
    function printConsolidated() {
        let element = document.getElementById('printReport');
        let opt = {
            margin: 0.2,
            filename: 'Consolidated Annual Procurement Plan {{ Auth::user()->ppmp_year }}',
            jsPDF: { unit: 'in', format: 'a4' }
        };
        let toPrint = element.innerHTML;
        const worker = html2pdf().set(opt).from(toPrint).save();
    }
</script>
@endif
@include('layout/footer')