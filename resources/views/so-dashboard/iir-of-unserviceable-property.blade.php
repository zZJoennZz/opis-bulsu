<x-dashboard-layout>
    <x-slot:title>
        Inventory and Inspection Report of Unserviceable Semi-Expendable Property
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Inventory and Inspection Report of Unserviceable Semi-Expendable Property'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />

    <div class="mb-3">
        <div class="m-auto">
            <form onsubmit="return confirm('Are you sure to generate this report as of {{ date('Y-m-d') }}')" action="{{ route('iirup.generate') }}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Email address</label>
                    <select class="form-select" aria-label="Default select example">
                        <option selected>Open this select menu</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-lg btn-primary"><em class="bi bi-gear-fill"></em> Generate Report</button>
                </div>
            </form>
        </div>
    </div>
    <div class="mb-5 table-responsive">
        <table class="table table-sm caption-top" id="generated-report">
            <caption>Generated Inventory and Inspection Report of Unserviceable Properties</caption>
            <thead>
                <tr>
                    <th style="width: 90%;">Date Generated</th>
                    <th style="width: 10%;" class="text-end">Print</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($generatedReports as $report)
                    <td>{{ date('Y-m-d h:i A', strtotime($report->created_at)) }}</td>
                    <td class="text-end"><a href="{{ route('iirup.print', ['reportId' => $report->id]) }}" target="_blank" class="btn btn-success btn-sm"><em class="bi bi-printer-fill"></em></a></td>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-hover caption-top" id="unserviceable-properties">
            <caption>View/Edit Property Data</caption>
            <thead>
                <tr>
                    <th>Date Acquired</th>
                    <th>Particulars</th>
                    <th>Unit Cost</th>
                    <th>Property Condition</th>
                    <th>Accumulated Depreciation</th>
                    <th>Accumulated Impairment Losses</th>
                    <th>Carrying Amount</th>
                    {{-- <th>Disposal</th>
                    <th>Appraised Value</th>
                    <th>OR Number</th>
                    <th>Amount</th> --}}
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($unavailableProperties as $property)
                <tr>
                    <td>{{ $property->item->transaction->date_acquired }}</td>
                    <td>{{ $property->item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description . ', ' . $property->item->bac_reso_item->quotation->brand_and_model_offered . ', S/N: ' . $property->serial_number }}</td>
                    <td>{{ number_format($property->item->bac_reso_item->quotation->offered_unit_price, 2) }}</td>
                    <td>{{ $property->property_condition ?? "n/a" }}</td>
                    <td>{{ $property->accumulated_depreciation ?? "n/a" }}</td>
                    <td>{{ $property->accumulated_impairment_losses ?? "n/a" }}</td>
                    <td>{{ $property->carrying_amount ?? "n/a" }}</td>
                    {{-- <td>{{ $property->disposal ?? "n/a" }}</td>
                    <td>{{ $property->appraised_value ?? "n/a" }}</td>
                    <td>{{ $property->or_number ?? "n/a" }}</td>
                    <td>{{ $property->amount ?? "n/a" }}</td> --}}
                    <td>
                        <form method="POST" action="{{ route('property.edit') }}">
                            @csrf
                            <input type="hidden" name="propertyId" value="{{ $property->id }}">
                            <button type="submit" class="btn btn-success btn-sm"><em class="bi bi-pencil-square"></em></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <x-slot:additional_script>
    @include('layout/datatable', ["tableId" => "unserviceable-properties"])

    <script defer>
        $(document).ready(function() {
            $('#generated-report').DataTable(
                {
                    "autoWidth": false,
                }
            );
        });
    </script>
    </x-slot>
</x-dashboard-layout>