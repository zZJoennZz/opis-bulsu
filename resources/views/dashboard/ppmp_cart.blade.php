@include('layout/header', ['title' => 'Project Procurement Management Plan Cart | OPIS - BulSU e-PROCUREMENT'])
@include('layout/member_header')
<div class="container-fluid">
    <div class="row">
        @include('layout/sidebar')

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="p-2">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-12">
                                <a href="{{ route('dashboard.show') }}" type="button" class="btn btn-warning"><em class="bi bi-plus"></em> Add new item</a>
                                @if(count($cart_items) > 0)
                                    <button type="button" class="submit-btn btn btn-success" onclick="submitCart()"><em class="bi bi-save2"></em> Submit</button>
                                @endif
                                <a class="submit-btn btn btn-info" href="{{ route('ppmp-activity-log.show', ['branch_id' => Auth::user()->branches_id]) }}"><em class="bi bi-clock-history"></em> Changes History</a>
                            </div>
                        </div>
                        <div class="table-responsive mb-3">
                            <table class="table table-sm table-bordered border-dark caption-top" id="ppmp-cart">
                                <caption>Project Procurement Management Plan Cart <span class="badge text-bg-primary">Year <strong>{{ Auth::user()->ppmp_year }}</strong></span></caption>
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
                                        <th rowspan="2" scope="col">Actions</th>
                                    </tr>
                                    <tr>
                                        @foreach ($ppmp_format as $format)
                                            <th id="{{ $format->id }}">{{ $format->name }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php ($totalTotalAmount = 0)
                                    @foreach ($cart_items as $item)
                                        @php ($totalAmount = 0)
                                        @php ($totalQty = 0)
                                        <tr @if ($item->is_priority === 1) class="table-primary" @endif>
                                            <td>{{ $item->description }}</td>
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
                                            <td>
                                                <a href="{{ route('get-ppmp-record.show', ['ppmp_id' => $item->id]) }}" type="button" class="btn btn-primary m-1"><em class="bi bi-pencil-fill"></em></a>
                                                <button type="button" onclick="deleteFromCart({{$item->id}})" class="btn btn-danger m-1"><em class="bi bi-trash-fill"></em></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td colspan="{{ count($ppmp_format) + 4 }}" class="fs-3 text-uppercase text-end">
                                            <strong>Total Amount</strong>
                                        </td>
                                        <td colspan="3" class="fs-3 text-uppercase text-start">
                                            ₱{{ number_format($totalTotalAmount, 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <a href="{{ route('dashboard.show') }}"  type="button" class="btn btn-warning"><em class="bi bi-plus"></em> Add new item</a>
                                @if(count($cart_items) > 0)
                                    <button type="button" class="submit-btn btn btn-success" onclick="submitCart()"><em class="bi bi-save2"></em> Submit</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
<script src="{{ asset('build/assets/app.b487754a.js') }}"></script>
<script defer>
    async function deleteFromCart(itemId) {
        let confirmDelete = confirm("Are you sure to delete this item from the cart?");
        if (confirmDelete) {
            await axios.delete(`{{url('/delete-ppmp-record')}}/${itemId}`)
                .then(res => {
                    window.location.reload();
                })
                .catch(err => {
                    window.location.reload();
                })
        }
    }
    async function submitCart() {
        let a = confirm("Are you sure to submit this cart?");
        if (a) {
            let submitBtn = document.getElementsByTagName('button');
            for(let i = 0; i < submitBtn.length; i ++) {
                submitBtn[i].disabled = true;
            }
            let data = [@foreach ($cart_items as $item){{ $item->id }},@endforeach];
            await axios.post('{{ route('ppmp-cart.submit') }}', data)
                .then(res => {
                    alert("Submission success! Please wait...");
                    window.location.href = '{{ route('ppmp-request.get') }}';
                }).catch(err  => {
                    console.error(err);
                    alert("Submission failed. Please try again! If the problem persists, please contact web administrator.");
                    // window.location.reload();
                });
        }
    }
</script>
@include('layout/datatable', ["tableId" => "ppmp-cart"])
@include('layout/footer')