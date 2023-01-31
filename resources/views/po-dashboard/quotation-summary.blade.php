@include('layout/header', ['title' => 'Price Quotation Summary | OPIS - BulSU e-PROCUREMENT'])
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
                                ['name' => 'Price Quotations', 'route' => 'quotation.all'],
                                ['name' => 'Price Quotation Summary'],
                            ]
                        ]
                        )
                        <div class="mb-3">

                            <button 
                            @if (count($quotation_summaries) > 1)
                            onclick="window.print()"
                            @else
                            onclick="alert('there is no printable data')"
                            @endif
                            class="btn btn-primary">
                                <em class="bi bi-printer-fill"></em> Print
                            </button>

                        </div>
                        <div class="m-auto for-q-print pt-0">
                            <div class="row mb-3 d-none d-print-flex" style="margin-top: -50px">
                                <div class="col-2"></div>
                                <div class="col-8 text-center fw-bold">
                                    <img src="{{ asset('img/bsu-small-logo.png') }}" alt="bsu logo" width="100" style="float: left;" />
                                    <div class="h-100 d-flex align-content-center flex-column justify-content-center">
                                        <div>Republic of Philippines</div>
                                        <div class="fs-4 mb-2">Bulacan State University</div>
                                        <div class="fs-6">City of Malolos, Bulacan</div>
                                    </div>
                                </div>
                                <div class="col-2"></div>
                            </div>
                            <div class="row d-none d-print-flex mb-3">
                                <div class="col-12">
                                    <div class="text-center fs-4 fw-bold">
                                        Price Quotation Summary Report
                                    </div>
                                </div>
                            </div>
                            <div class="row table-responsive">
                                <table class="table table-sm table-bordered" id="quotation-list-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 30%;">Company</th>
                                            <th style="width: 50%;">Item Detail</th>
                                            <th style="width: 20%;">Unit Bid Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      @if (count($quotation_summaries) > 1)
                                      @foreach ($quotation_summaries as $summary)
                                            <tr>
                                                <td colspan="3">{{$summary->name}}</td>
                                            </tr>
                                            @foreach ($summary->quotations as $quote)
                                                @foreach ($quote->items as $item)
                                                    @if ($item->ppmp !== null)
                                                        <tr>
                                                            <td></td>
                                                            <td>{{ $item->ppmp->item_detail->description }}</td>
                                                            <td>₱ {{ number_format($item->offered_unit_price, 2) }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                      @else
                                      <tr>
                                        <td colspan="3">
                                            <dt style="text-align:center;font-weight:300;font-family:sans-serif;padding:10px 0px;">no summary of quotation</dt>
                                        </td>
                                      </tr>
                                      @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
@include('layout/footer')
