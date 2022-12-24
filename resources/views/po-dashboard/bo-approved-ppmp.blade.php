@include('layout/header', ['title' => 'PPMP Approval | OPIS - BulSU e-PROCUREMENT'])
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
            <div class="pt-3">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-4">
                            <a href="{{ route('bo-dashboard.show') }}" class="btn btn-secondary"><em class="bi bi-arrow-bar-left"></em> Back</a>
                            <button class="btn btn-success" type="button" onclick="submitApprove()"><em class="bi bi-check2-square"></em> Approve</button>
                            <button class="btn btn-warning" type="button" onclick="sendBack()"><em class="bi bi-arrow-90deg-up"></em> Send Bank</button>
                            <a href="{{ route('ppmp-activity-log.show', ['branch_id' => $ppmp_items[0]->branches_id]) }}" class="btn btn-info float-end"><em class="bi bi-clock-history"></em> PPMP Changes History Logs</a>
                        </div>
                        <hr />
                        <div class="mb-4">
                            <span class="badge text-bg-primary"><em class="bi bi-check-circle-fill"></em> Budget Office</span>
                        </div>
                        <div class="table-responsive mb-3">
                            <table class="table table-sm table-bordered border-dark caption-top" id="ppmp-approval-table">
                                <caption>New Project Procurement Management Plan Requests <span class="badge text-bg-primary">Year <strong>{{ Auth::user()->ppmp_year }}</strong></span></caption>
                                <thead class="text-center">
                                    <tr>
                                        <th rowspan="2" scope="col">Item Description</th>
                                        <th rowspan="2" scope="col">Unit of Measurement</th>
                                        <th rowspan="2" scope="col">Estimated Budget</th>
                                        <th colspan="{{ count($ppmp_format) }}" id="milestones">Schedule/Milestone of Activities</th>
                                        <th rowspan="2" scope="col">Total Qty</th>
                                        <th rowspan="2" scope="col">Price Catalogue</th>
                                        <th rowspan="2" scope="col">Total Amount</th>
                                        <th rowspan="2" scope="col">Remarks</th>
                                        <th rowspan="2" scope="col">Edit</th>
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
                                            <td><a href="{{ route('get-ppmp-record.show', ['ppmp_id' => $item->id]) }}" class="btn btn-success"><em class="bi bi-pencil-square"></em></a></td>
                                        </tr>
                                    @endforeach
                                    
                                </tbody>
                                <tr>
                                    <td colspan="{{ count($ppmp_format) + 4 }}" class="fs-3 text-uppercase text-end">
                                        <strong>Total Amount</strong>
                                    </td>
                                    <td colspan="4" class="fs-3 text-uppercase text-start">
                                        ₱{{ number_format($totalTotalAmount, 2) }}
                                    </td>
                                </tr>
                            </table>
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
    async function submitApprove() {
        let a = confirm("Are you sure to approve this PPMP?");
        if (a) {
            let submitBtn = document.getElementsByTagName('button');
            for(let i = 0; i < submitBtn.length; i ++) {
                submitBtn[i].disabled = true;
            }
            let data = [@foreach ($ppmp_items as $item){{ $item->id }},@endforeach];
            await axios.post('{{ route('po-approve-ppmp-approval.perform') }}', data)
                .then(res => {
                    alert("Approval success! Please wait...");
                    window.location.href = '{{ route('dashboard.show') }}';
                }).catch(err  => {
                    console.error(err);
                    alert("Approval failed. Please try again! If the problem persists, please contact web administrator.");
                    window.location.reload();
                });
        }
    }

    async function sendBack() {
        let a = confirm("Are you sure to send this back to the user?");
        if (a) {
            let submitBtn = document.getElementsByTagName('button');
            for (let i = 0; i < submitBtn.length; i ++) {
                submitBtn[i].disabled = true;
            }
            let data = [@foreach ($ppmp_items as $item){{ $item->id }},@endforeach];
            await axios.post('{{ route('send-back-ppmp-approval.perform', ['user_id' => $ppmp_items[0]->submitted_by]) }}', data)
                .then(res => {
                    alert(res.data.message);
                    window.location.href = '{{ route('dashboard.show') }}';
                })
                .catch(err => {
                    alert("Something went wrong. The changes were saved but not sent back to the user.")
                    window.location.reload();
                });
        }
    }
</script>
@include('layout/datatable', ['tableId' => 'ppmp-approval-table'])
@include('layout/footer')