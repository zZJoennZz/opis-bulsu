<x-dashboard-layout>
    <x-slot:title>
        Item Purpose
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Item Purpose List'],
        ]
    @endphp
    <div class="modal fade" id="addNewItemPurpose" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('item-purpose.add') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Item Purpose</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="description" name="description" placeholder="Description">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><em class="bi bi-save"></em> Save Purpose</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editPurpose" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form onsubmit="return saveChanges(event)">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="edit-cat-header-text">Edit Item Purpose</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="edit_description" name="description" placeholder="Description">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><em class="bi bi-save"></em> Save Purpose</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <x-breadcrumb :breadcrumb="$breadcrumb" />
    <h1 class="h5 card-title"> <span class="float-end small"># of records: <span class="badge text-bg-secondary">{{ count($item_purposes) }}</span></span></h1>
    <div class="mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewItemPurpose"><em class="bi bi-folder-plus"></em> Add</button>
        <button class="btn btn-danger" onclick="deleteRecord()"><em class="bi bi-trash"></em> Delete</button>
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-bordered" id="item-purpose-table">
            <caption>Item Purpose List</caption>
            <thead>
                <tr>
                    <th style="width: 5%;"></th>
                    <th class="text-center" style="width: 5%;">Edit</th>
                    <th style="width: 80%;">Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($item_purposes as $purpose)
                    <tr>
                        <td>
                            <div class="form-check w-100 d-flex align-items-center justify-content-center">
                                <input class="form-check-input itempurpose-checkbox" type="checkbox" value="{{ $purpose->id }}" id="itempurpose{{ $purpose->id }}" @if($purpose->is_delete===1) disabled @endif>
                            </div>
                        </td>
                        <td class="text-center"><button class="btn btn-success" onclick="openEdit({{ $purpose->id }})" @if($purpose->is_delete===1) disabled @endif><em class="bi bi-pencil-square"></em></button></td>
                        <td>{{ $purpose->description }} @if($purpose->is_delete===1) <span class="badge bg-secondary">Item Purpose Deleted</span> @else <button type="button" class="btn btn-danger" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" onclick="deleteRecord({{$purpose->id}})"><em class="bi bi-trash-fill"></em></button> @endif</td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <x-slot:additional_script>
        <script src="{{ asset('build/assets/app.b487754a.js') }}"></script>
        <script>
            let selectedPurpose = 0;
        
            async function openEdit(id) {
                selectedPurpose = id;
                await axios.get(`{{ url('/item-purpose') }}/${id}`)
                    .then(res => {
                        $('#edit_description').val(res.data.description);
                        $('#editPurpose').modal('toggle');
                    })
                    .catch(err => alert("Could not fetch the data. Please contact website administrator."));
            }
        
            async function saveChanges(e) {
                e.preventDefault();
                const data = {
                    "description" : $('#edit_description').val(),
                };
                await axios.put(`{{ url('/item-purpose') }}/${selectedPurpose}`, data)
                    .then(res => {
                        {{Session::forget('success');}}
                        window.location.reload();
                    })
                    .catch(err => {
                        window.location.reload();
                        alert("Could not fetch the data. Please contact website administrator.");
                        console.log("Could not fetch the data. Please contact website administrator.");
                    });
        
                return false;
            }
        
            async function deleteRecord(id = null){
                if (id === null) {
                    let allSelectedItemPurpose = $(".itempurpose-checkbox");
                    let toDelete = [];
                    for (let i = 0; i < allSelectedItemPurpose.length; i ++) {
                        if (allSelectedItemPurpose[i].checked) {
                            toDelete.push(allSelectedItemPurpose[i].value);
                        }
                    }
                    if (toDelete.length > 0) {
                        let confirmDeleteBatch = confirm("Are you sure to delete?");
                        if (confirmDeleteBatch) {
                            await axios.post(`{{ route('item-purpose.delete_batch') }}`, { id : toDelete })
                                .then(res => {
                                    window.location.reload();
                                }).catch(err => {
                                    window.location.reload();
                                });
                        }
                    } else {
                        alert("Select item purpose to delete first!");
                    }
                } else {
                    let confirmDeleteSingle = confirm("Are you sure to delete this item purpose?");
                    if (confirmDeleteSingle) {
                        await axios.delete(`{{ url('item-purpose/single') }}/${id}`)
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
        @include('layout/datatable', ['tableId' => 'item-purpose-table'])
    </x-slot>
</x-dashboard-layout>