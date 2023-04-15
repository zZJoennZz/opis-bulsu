
<x-dashboard-layout>

    <x-slot:title>
        Manage Equipment Code
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Manage Equipment Code'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    <x-supplier-header/>
    
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-12">
                <h1 class="fw-bold h2 text-secondary">Manage Supply Position</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-5">
                <div class="card">
                    <div class="card-body">
                        <h2 class="h4 text-secondary mb-4">Add Equipment Code</h2>
                        <form action="{{ route('equipment-code.post_add') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="equipment_code" class="form-label">Equipment Code</label>
                                <input type="text" class="form-control" id="equipment_code" name="equipment_code" placeholder="Enter equipment code here." required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <input type="text" class="form-control" id="description" name="description" placeholder="Enter a description" required>
                            </div>
                            <div>
                                <button class="btn btn-primary"><em class="bi bi-save2"></em> Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-7 border p-2" style="height: 600px; overflow-y: scroll;">
                <div class="table-responsive">
                    <button class="btn btn-danger my-3" onclick="deleteRecord()"><em class="bi bi-trash"></em> Delete</button>
                    <table id="equipment-code-table" class="table table-sm border-dark caption-top">
                        <caption class="small text-secondary">Equipment code List</caption>
                        <thead>
                            <tr>
                                <th></th>
                                <th>Equipment Code</th>
                                <th>Description</th>
                                <th></th>
                                <th style="disply: none;">Created at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($equipment_codes as $equipment_code)
                                <tr class="small">
                                    <td class="py-3">
                                        <div class="form-check w-100 d-flex align-items-center justify-content-center">
                                            <input class="form-check-input delete-checkbox p-2 border border-dark" type="checkbox" value="{{ $equipment_code->id }}" id="enduser{{ $equipment_code->id }}" @if($equipment_code->is_delete===1) disabled @endif>
                                        </div>
                                    </td>
                                    <td class="py-3">{{ $equipment_code->equipment_code }} </td>
                                    <td class="py-3">{{ $equipment_code->description }} </td>
                                    <td class="text-end py-3">
                                        <div class="btn-group" role="group" aria-label="End user actions">
                                            <button onclick="openEditForm({{ $equipment_code->id }})" type="button" class="btn btn-primary btn-sm"><em class="bi bi-pencil-square"></em></button>
                                            @if($equipment_code->is_delete===1) <span class="badge bg-secondary rounded-0 rounded-end d-flex align-items-center">Deleted</span> @else <button type="button" class="btn btn-danger" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" onclick="deleteRecord({{$equipment_code->id}})"><em class="bi bi-trash-fill"></em></button> @endif
                                        </div>
                                    </td>
                                    <td style="display: none;">{{ $equipment_code->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- Update MODAL --}}
    <div class="modal fade" id="editEquipmentCode" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="openEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="openEditModalLabel">Edit End User</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form onsubmit="return saveChanges(event)">
                        @csrf
                        <div class="mb-3">
                            <label for="equipment_code" class="form-label">Equipment Code</label>
                            <input type="text" class="form-control" id="edit_equipment_code" name="equipment_code">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="edit_description" name="description">
                        </div>
                        <div>
                            <button  type="submit" class="btn btn-primary"><em class="bi bi-save2"></em> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-slot:additional_script>
        @include('layout/datatable', ['tableId' => 'equipment-code-table' , 'columnId' => '4'])

        <script src="{{ asset('build/assets/app.b487754a.js') }}"></script>
        <script>
            let selectedEquipmentCode = 0;
        
            async function openEditForm(id) {
                selectedEquipmentCode = id;
                await axios.get(`{{ url('/manage-equipment-code') }}/${id}`)
                    .then(res => {
                        $('#edit_equipment_code').val(res.data.equipment_code);
                        $('#edit_description').val(res.data.description);
                        $('#editEquipmentCode').modal('toggle');
                    })
                    .catch(err => alert("Could not fetch the data. Please contact website administrator."));
            }        

                    
            async function saveChanges(e) {
                e.preventDefault();
                const data = {
                    "name" : $('#edit_equipment_code').val(),
                    "type" : $('#edit_description').val(),
                };
                await axios.put(`{{ url('/manage-equipment-code') }}/${selectedEquipmentCode}`, data)
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
                    let allSelectedEnduser = $(".delete-checkbox");
                    let toDelete = [];
                    for (let i = 0; i < allSelectedEnduser.length; i ++) {
                        if (allSelectedEnduser[i].checked) {
                            toDelete.push(allSelectedEnduser[i].value);
                        }
                    }
                    if (toDelete.length > 0) {
                        let confirmDeleteBatch = confirm("Are you sure to delete?");
                        if (confirmDeleteBatch) {
                            await axios.post(`{{ route('equipmentcode.delete_batch') }}`, { id : toDelete })
                                .then(res => {
                                    window.location.reload();
                                }).catch(err => {
                                    window.location.reload();
                                });
                        }
                    } else {
                        alert("Select Equipment Code to delete first!");
                    }
                } else {
                    let confirmDeleteSingle = confirm("Are you sure to delete this equipment code from the list?");
                    if (confirmDeleteSingle) {
                        await axios.delete(`{{ url('manage-equipment-code/single') }}/${id}`)
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
    </x-slot>
</x-dashboard-layout>