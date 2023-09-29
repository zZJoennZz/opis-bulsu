<x-dashboard-layout>
    <x-slot:title>
        Properties
        </x-slot>

        @php
        $breadcrumb = [
        ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
        ['name' => 'Properties'],
        ]
        @endphp

        <x-breadcrumb :breadcrumb="$breadcrumb" />
        <div class="table-responsive mb-3">
            <table class="table table-sm table-hover border-dark caption-top" id="all-items">
                <caption></caption>
                <thead class="small">
                    <tr>
                        <th>Actions</th>
                        <th>Type</th>
                        <th style="width: 40%;">Item</th>
                        <th>Date Acquired</th>
                        <th style="width: 30%;">Current Keeper</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $idBefore = 0;
                    @endphp
                    @foreach ($items as $item)
                    @if (count($item->transfers) > 0)
                    <tr>
                        <td>
                            <div class="btn-group btn-group-sm" role="group" aria-label="actions button group">
                                <a href="{{ route('property.single', ['propertyId' => $item->id]) }}" class="btn btn-outline-primary"><em
                                        class="bi bi-eye-fill"></em></a>
                                @if ($item->item->equipment_code->article === "NON_SEMI_EXPENDABLE")
                                <a href="{{ route('maintenance.select', ['id' => $item->id]) }}" class="btn btn-outline-primary"><em class="bi bi-wrench-adjustable"></em></a>
                                @endif
                                <a href="{{ route('user.transfer', ['propertyId' => $item->id]) }}" class="btn btn-outline-primary"><em
                                        class="bi bi-arrow-left-right"></em></a>
                            </div>
                        </td>
                        <td>
                            @if ($item->item->transaction->type === "PAR")
                            <span class="badge bg-primary">PAR</span>
                            @endif
                            @if ($item->item->transaction->type === "ICSL")
                            <span class="badge bg-secondary">ICS <em class="bi bi-caret-down-fill"></em></span>
                            @endif
                            @if ($item->item->transaction->type === "ICSH")
                            <span class="badge bg-success">ICS <em class="bi bi-caret-up-fill"></em></span>
                            @endif
                        </td>
                        <td>
                            {{ $item->item->bac_reso_item->quotation->brand_and_model_offered }} /
                            {{ $item->item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}
                        </td>
                        <td>{{ $item->item->transaction->date_acquired }}</td>
                        <td>
                            @foreach ($item->current_owners as $owner)
                            <div>
                                {{ $owner->end_user->first_name . " " . $owner->end_user->middle_name . " " . $owner->end_user->last_name }}
                            </div>
                            <div class="small text-uppercase fst-italic text-muted">
                                {{ $owner->end_user->position->name }} / {{ $owner->end_user->branch->branch_name }}
                            </div>
                            @endforeach
                        </td>
                    </tr>
                    @else

                    @if (intval($idBefore) !== intval($item->item->id))
                    <tr>
                        <td>
                            <div class="btn-group btn-group-sm" role="group" aria-label="actions button group">
                                <a href="{{ route('property.single', ['propertyId' => $item->id]) }}" class="btn btn-outline-primary"><em
                                        class="bi bi-eye-fill"></em></a>
                                    @if ($item->item->equipment_code->article === "NON_SEMI_EXPENDABLE")
                                    <a href="{{ route('maintenance.select', ['id' => $item->id]) }}" class="btn btn-outline-primary"><em class="bi bi-wrench-adjustable"></em></a>
                                    @endif
                                <a href="{{ route('transfer_ics.get', ['id' => $item->item->id]) }}" class="btn btn-outline-primary"><em
                                        class="bi bi-arrow-left-right"></em></a>
                            </div>
                        </td>
                        <td>
                            @if ($item->item->transaction->type === "PAR")
                            <span class="badge bg-primary">PAR</span>
                            @endif
                            @if ($item->item->transaction->type === "ICSL")
                            <span class="badge bg-secondary">ICS <em class="bi bi-caret-down-fill"></em></span>
                            @endif
                            @if ($item->item->transaction->type === "ICSH")
                            <span class="badge bg-success">ICS <em class="bi bi-caret-up-fill"></em></span>
                            @endif
                        </td>
                        <td>
                            {{ $item->item->bac_reso_item->quotation->brand_and_model_offered }} /
                            {{ $item->item->bac_reso_item->quotation->pr_item->ppmp->item_detail->description }}
                            @php
                            $availableQty = 0;
                            foreach ($item->item->properties as $property) {
                            if (count($property->transfers) === 0) {
                            $availableQty += 1;
                            }
                            }
                            @endphp
                            <div class="small text-success">({{ $availableQty }} {{
                                pluralize($item->item->bac_reso_item->quotation->pr_item->ppmp->item_detail->unit->uom, $availableQty) }} available)
                            </div>
                        </td>
                        <td>{{ $item->item->transaction->date_acquired }}</td>
                        <td>
                            @foreach ($item->current_owners as $owner)
                            <div>
                                {{ $owner->end_user->first_name . " " . $owner->end_user->middle_name . " " . $owner->end_user->last_name }}
                            </div>
                            <div class="small text-uppercase fst-italic text-muted">
                                {{ $owner->end_user->position->name }} / {{ $owner->end_user->branch->branch_name }}
                            </div>
                            @endforeach
                        </td>
                    </tr>
                    @endif
                    @php
                    $idBefore = $item->item->id;
                    @endphp
                    @endif

                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="text-muted fst-italic small">Can't find item? Check <a href="{{ route('par.all') }}">PAR</a> or <a
                href="{{ route('ics.all') }}">ICS</a>.</div>
        <x-slot:additional_script>
            @include('layout/datatable', ['tableId' => 'all-items'])
            </x-slot>
</x-dashboard-layout>