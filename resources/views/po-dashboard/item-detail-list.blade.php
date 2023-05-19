<x-dashboard-layout>
    <x-slot:title>
        Item Details
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>'],
            ['name' => 'Item Details List'],
        ]
    @endphp
    <span class="float-end small"># of records: <span class="badge text-bg-secondary">{{ count($item_details) }}</span></span>
    <x-breadcrumb :breadcrumb="$breadcrumb" />
    <div class="mb-4">
        <a class="btn btn-primary" href="{{ route('add-new-item.show') }}"><em class="bi bi-folder-plus"></em> Add</a>
        <button class="btn btn-danger" onclick="deleteRecord()"><em class="bi bi-trash"></em> Delete</button>
        <a href="{{ route('pending-item-detail.show') }}" class="btn btn-secondary"><em class="bi bi-clock-history"></em> Pending Updates</a>
    </div>
    <div class="table-responsive p-3">
        <table class="table table-sm table-hover" id="item-details-table">
            <caption>Item Details</caption>
            <thead>
                <tr>
                    <th style="width: 5%;"></th>
                    <th class="text-center" style="width: 5%;">Edit</th>
                    <th style="width: 5%">Status</th>
                    <th style="width: 30%;">Item Name</th>
                    <th style="width: 20%">Unit</th>
                    <th style="width: 25%">Category</th>
                    <th style="width: 15%;">Date Added</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($item_details as $item)
                <tr>
                    <td>
                        <div class="form-check w-100 d-flex align-items-center justify-content-center">
                            <input class="form-check-input item-checkbox" type="checkbox" value="{{ $item->id }}" id="category{{ $item->id }}" @if($item->is_delete===1) disabled @endif>
                        </div>
                    </td>
                    <td class="text-center">
                        <a class="btn btn-sm @if($item->is_approve === 0) btn-secondary @else btn-primary @endif" @if($item->is_delete===1) style="opacity: 0.5;" @else href="{{ route('view-item-detail.show', ['item_detail_id' => $item->id]) }}" @endif>@if($item->is_approve === 0) <em class="bi bi-eye"></em> @else <em class="bi bi-pencil-square"></em> @endif</a>
                    </td>
                    <td>
                        @if($item->is_approve === 1 && $item->is_delete === 0)
                            <span class="badge rounded-pill text-bg-primary"><em class="bi bi-check-circle-fill"></em></span>
                        @else
                            @if ($item->is_approve === 0 && $item->is_delete === 0)
                                <span class="badge rounded-pill text-bg-secondary"><em class="bi bi-clock-history"></em></span>
                            @endif
                            @if ($item->is_approve === 0 || $item->is_delete === 1)
                                <span class="badge rounded-pill text-bg-secondary"><i class="bi bi-x-circle-fill"></i></span>
                            @endif
                        @endif
                    </td>
                    <td>
                        <span
                            data-bs-toggle="tooltip"
                            data-bs-placement="bottom"
                            data-bs-title="{{ $item->article }}"
                        >
                            {{ $item->description }}
                        </span>
                        @if($item->is_delete===1) <span class="badge bg-secondary">Item Deleted</span> @else <button type="button" class="btn btn-danger" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" onclick="deleteRecord({{$item->id}})"><em class="bi bi-trash-fill"></em></button> @endif
                    </td>
                    <td>{{$item->unit->uom}}</td>
                    <td>{{$item->category->description}}</td>
                    <td class="small">{{date('Y-m-d h:iA', strtotime($item->created_at))}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <x-slot:additional_script>
        <script src="{{ asset('build/assets/app.b487754a.js') }}"></script>
        <script>
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
        </script>
        <script>
            let selectedCategory = 0;
        
            async function openEdit(id) {
                selectedCategory = id;
                await axios.get(`{{ url('/item-categories') }}/${id}`)
                    .then(res => {
                        $('#edit_description').val(res.data.description);
                        $('#edit_under_of_group').val(`${res.data.under_of_group}`).change();
                        $('#editCategory').modal('toggle');
                    })
                    .catch(err => alert("Could not fetch the data. Please contact website administrator."));
            }
        
            async function saveChanges(e) {
                e.preventDefault();
                const data = {
                    "description" : $('#edit_description').val(),
                    "under_of_group" : $('#edit_under_of_group').val()
                };
                await axios.put(`{{ url('/item-categories') }}/${selectedCategory}`, data)
                    .then(res => {
                        {{Session::forget('success');}}
                        window.location.reload();
                    })
                    .catch(err => {
                        window.location.reload();
                        console.log("Could not fetch the data. Please contact website administrator.")
                    });
        
                return false;
            }
        
            async function deleteRecord(id = null){
                if (id === null) {
                    let allSelectedItem = $(".item-checkbox");
                    let toDelete = [];
                    for (let i = 0; i < allSelectedItem.length; i ++) {
                        if (allSelectedItem[i].checked) {
                            toDelete.push(allSelectedItem[i].value);
                        }
                    }
                    if (toDelete.length > 0) {
                        let confirmDeleteBatch = confirm("Are you sure to delete?");
                        if (confirmDeleteBatch) {
                            await axios.post(`{{ route('item-detail-list.delete_batch') }}`, { id : toDelete })
                                .then(res => {
                                    window.location.reload();
                                }).catch(err => {
                                    window.location.reload();
                                });
                        }
                    } else {
                        alert("Select item to delete first!");
                    }
                } else {
                    let confirmDeleteSingle = confirm("Are you sure to delete this category?");
                    if (confirmDeleteSingle) {
                        await axios.delete(`{{ url('/item-details/single') }}/${id}`)
                            .then(res => {
                                window.location.reload();
                            })
                            .catch(err => {
                                window.location.reload();
                            });
                    }
                }
            }
        </script>
        @include('layout/datatable', ['tableId' => 'item-details-table'])
    </x-slot>
</x-dashboard-layout>