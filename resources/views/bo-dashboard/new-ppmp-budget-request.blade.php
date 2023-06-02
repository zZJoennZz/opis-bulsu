<x-dashboard-layout>
    <x-slot:title>
        Pending Budget Request
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Pending PPMP']
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    <form method="POST" action="{{ route('bo-approve-ppmp-request.perform') }}" onsubmit="return confirm('Are you sure to approve this?')">
        @csrf
        @method('PUT')
        <input type="hidden" name="branch" id="branch" value="{{ $branch_id }}">
        <div class="mb-3">
            <a href="{{ route('bo-dashboard.show') }}" class="btn btn-sm btn-secondary"><em class="bi bi-arrow-bar-left"></em> Back</a>
            <button class="btn btn-sm btn-warning" type="button" onclick="sendBack()"><em class="bi bi-arrow-90deg-up"></em> Send Back</button>
        </div>
        <div class="mb-3">
            <div class="row">
                <div class="col-sm-12">
                    <label for="source_of_funds_id" class="form-label">PPMP source of fund: <span class="badge bg-primary">{{ getPpmpYear() }}</span></label>
                    <select class="form-select" id="source_of_funds_id" name="source_of_funds_id" aria-label="Purpose of the item">
                        @foreach ($source_of_funds as $source)
                        <option value="{{$source->id}}" @php echo $currentSourceOfFund === $source->id ? "selected" : "" @endphp>{{$source->source_of_fund}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-12 mt-2">
                    <button class="btn btn-sm btn-primary" type="submit"><em class="bi bi-check2-square"></em> Approve</button>
                </div>
            </div>
        </div>
    </form>
    <div class="table-responsive mb-3">
        <table class="table table-sm table-hover border-dark caption-top" id="ppmp-cart">
            <caption>New Project Procurement Management Plan Requests <span class="badge text-bg-primary">Year <strong>{{ Auth::user()->ppmp_year }}</strong></span></caption>
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
                        <td><a href="{{ route('get-ppmp-record.show', ['ppmp_id' => $item->id]) }}" class="btn btn-sm btn-success"><em class="bi bi-pencil-square"></em></a></td>
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

    <x-slot:additional_script>
        @vite('resources/js/app.js')

        <script defer>
            async function submitApprove() {
                if ($('#source_of_funds_id').val() === "1") {
                    alert('Select source of fund before approving!');
                    return;
                }
                let a = confirm("Are you sure to approve this PPMP?");
                if (a) {
                    let submitBtn = document.getElementsByTagName('button');
                    for(let i = 0; i < submitBtn.length; i ++) {
                        submitBtn[i].disabled = true;
                    }
                    let data = [@foreach ($ppmp_items as $item){{ $item->id }},@endforeach];
                    await axios.post('{{ route('bo-approve-ppmp-request.perform') }}', data)
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
                    await axios.post('{{ route('send-bank-ppmp.perform', ['user_id' => $ppmp_items[0]->submitted_by]) }}', data)
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
    </x-slot>
</x-dashboard-layout>