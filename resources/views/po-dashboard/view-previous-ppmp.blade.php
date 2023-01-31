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
                            @include('layout/breadcrumb',
                            [
                                'breadcrumbs' => [
                                    ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
                                    ['name' => 'Previous PPMP Records', 'routeWithParam' => route('previous-ppmp.show', ['branch_id' => $branch->id])],
                                    ['name' => 'PPMP <span class="badge bg-primary">'.  $record_year .'</span>']
                                ]
                            ]
                            )
                        </div>

                        <table class="table table-sm table-bordered border-dark caption-top">
                            <caption>
                                <div class="h3">PROJECT PROCUREMENT MANAGEMENT PLAN (PPMP) <span class="badge bg-primary">{{ $record_year }}</span></div>
                                <div class="h4">
                                    END-USER / UNIT: <strong>{{ $branch->branch_name }}</strong>
                                </div>
                            </caption>
                            <thead class="text-uppercase text-center">
                                <tr>
                                    <th scope="col" rowspan="2">No.</th>
                                    <th scope="col" rowspan="2">General Description</th>
                                    <th style="width: 5%;" scope="col" rowspan="2">Unit of Measurement</th>
                                    <th scope="col" colspan="{{ count(json_decode($ppmp_format->format)) }}">Milestone of Activities</th>
                                    <th scope="col" rowspan="2">Price Catalogue</th>
                                    <th scope="col" rowspan="2">Total Amount</th>
                                </tr>
                                <tr>
                                    @foreach (json_decode($ppmp_format->format) as $format)
                                        <th id="{{ $format->id }}">{{ $format->name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $grand_total = 0;
                                @endphp
                                @foreach ($ppmp_report as $section)
                                    @php
                                        $subtotal_section = 0;
                                    @endphp
                                    <tr>
                                        <td class="fs-4 fw-bold" colspan="{{ count(json_decode($ppmp_format->format)) + 4 }}">{{$section->title}}</td>
                                        <td></td>
                                    </tr>
                                        @foreach($section->category_groups as $group)
                                            @php
                                                $subtotal_category_group = 0;
                                            @endphp
                                            @if ($group->order !== 0)
                                                <tr class="bg-warning">
                                                    <td></td>
                                                    <td class="fs-5 fw-bold" colspan="{{ count(json_decode($ppmp_format->format)) + 3 }}">{{$group->title}}</td>
                                                    <td></td>
                                                </tr>
                                                @foreach ($group->categories as $category)
                                                    @php
                                                        $subtotal_category = 0;
                                                    @endphp
                                                    <tr>
                                                        <td></td>
                                                        <td class="fs-6 fw-bold" colspan="{{ count(json_decode($ppmp_format->format)) + 3 }}">{{$category->description}}</td>
                                                        <td></td>
                                                    </tr>
                                                    @php
                                                        $ctr = 1
                                                    @endphp
                                                    @foreach ($category->item_details as $item)
                                                        <tr>
                                                            <td class="text-center">
                                                                {{$ctr}}
                                                                @php
                                                                    $ctr += 1
                                                                @endphp
                                                            </td>
                                                            <td>{{$item->description}}</td>
                                                            <td class="text-center">{{$item->unit->uom}}</td>
                                                            @php
                                                                $totalQty = 0
                                                            @endphp
                                                            @foreach (json_decode($ppmp_format->format) as $format)
                                                                <td>
                                                                    @php
                                                                        $total = 0;
                                                                    @endphp
                                                                    @foreach ($item->ppmp as $ppmp)
                                                                        @foreach ($ppmp->milestones as $milestone)
                                                                            @if ($milestone->milestone_value_id === $format->id)
                                                                                @php
                                                                                    $total += $milestone->milestone_value
                                                                                @endphp
                                                                            @endif
                                                                        @endforeach
                                                                    @endforeach
                                                                    @php
                                                                        $totalQty += $total
                                                                    @endphp
                                                                    {{$total}}
                                                                </td>
                                                            @endforeach
                                                            <td>₱ <div class="float-end">{{$item->price_catalogue}}</div></td>
                                                            <td>
                                                                ₱ <div class="float-end">{{number_format($totalQty * $item->price_catalogue, 2)}}</div>
                                                                @php
                                                                    $subtotal_category += $totalQty * $item->price_catalogue;
                                                                @endphp
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td></td>
                                                        <td class="fs-6 fw-bold" colspan="{{ count(json_decode($ppmp_format->format)) + 3 }}">TOTAL, {{$category->description}}</td>
                                                        <td class="fs-6 fw-bold">
                                                            ₱ <div class="float-end">{{number_format($subtotal_category, 2)}}</div>
                                                            @php
                                                                $subtotal_category_group += $subtotal_category
                                                            @endphp
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr class="bg-warning">
                                                    <td></td>
                                                    <td class="fs-5 fw-bold" colspan="{{ count(json_decode($ppmp_format->format)) + 3 }}">TOTAL, {{$group->report_sub_total_footer}}</td>
                                                    <td class="fs-5 fw-bold">
                                                        ₱ <div class="float-end">{{number_format($subtotal_category_group, 2)}}</div>
                                                        @php
                                                            $subtotal_section += $subtotal_category_group
                                                        @endphp
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    <tr>
                                        <td class="fs-4 fw-bold" colspan="{{ count(json_decode($ppmp_format->format)) + 4 }}">TOTAL, {{$section->report_sub_total_footer}}</td>
                                        <td class="fs-4 fw-bold">
                                            ₱ <div class="float-end">{{number_format($subtotal_section, 2)}}</div>
                                            @php
                                                $grand_total += $subtotal_section
                                            @endphp
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="fs-3 fw-bold" colspan="{{ count(json_decode($ppmp_format->format)) + 4 }}">
                                        Grand Total
                                    </td>
                                    <td class="fs-3 fw-bold">₱ <div class="float-end">{{number_format($grand_total, 2)}}</div></td>
                                </tr>
                            </tfoot>
                        </table>

                        {{-- <table class="table table-sm table-bordered" id="ppmp-record">
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
                                <tr>
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
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td class="fs-3 fw-bold text-end" colspan="{{ count(json_decode($ppmp_format->format)) + 4 }}">TOTAL AMOUNT</td>
                                    <td class="fs-3 fw-bold text-start" colspan="2">₱{{ number_format($totalAmount, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table> --}}
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
@include('layout/datatable', ['tableId' => 'ppmp-record'])
@include('layout/footer')