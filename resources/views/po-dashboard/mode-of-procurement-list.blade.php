<x-dashboard-layout>
    <x-slot:title>
        Mode of Procurement
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Mode of Procurement List'],
        ]
    @endphp
    <div class="modal fade" id="addNewModeOfProcurement" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('mode-procurement.add') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Mode Of Procurement</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Mode of Procurement">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><em class="bi bi-save"></em> Save Mode Of Procurement</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModeProcurement" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form onsubmit="return saveChanges(event)">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="edit-cat-header-text">Edit Mode Of Procurement</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Description</label>
                            <input type="text" class="form-control" id="edit_name" name="name" placeholder="Mode of Procurement">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><em class="bi bi-save"></em> Save Mode Procurement</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    <h1 class="h5 card-title"> <span class="float-end small"># of records: <span class="badge text-bg-secondary">{{ count($mode_procurements) }}</span></span></h1>
    <div class="mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewModeOfProcurement"><em class="bi bi-folder-plus"></em> Add</button>
        <button class="btn btn-danger" onclick="deleteRecord()"><em class="bi bi-trash"></em> Delete</button>
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-bordered" id="item-mode-procurement-table">
            <caption>Mode of Procurement List</caption>
            <thead>
                <tr>
                    <th style="width: 5%;"></th>
                    <th class="text-center" style="width: 5%;">Edit</th>
                    <th style="width: 80%;">Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mode_procurements as $mode_procurement)
                    <tr>
                        <td>
                            <div class="form-check w-100 d-flex align-items-center justify-content-center">
                                <input class="form-check-input modeprocurement-checkbox" type="checkbox" value="{{ $mode_procurement->id }}" id="modeofprocurement{{ $mode_procurement->id }}" @if($mode_procurement->is_delete===1) disabled @endif>
                            </div>
                        </td>
                        <td class="text-center"><button class="btn btn-success" onclick="openEdit({{ $mode_procurement->id }})" @if($mode_procurement->is_delete===1) disabled @endif><em class="bi bi-pencil-square"></em></button></td>
                        <td>{{ $mode_procurement->name }} @if($mode_procurement->is_delete===1) <span class="badge bg-secondary">Mode of Procurement Deleted</span> @else <button type="button" class="btn btn-danger" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" onclick="deleteRecord({{$mode_procurement->id}})"><em class="bi bi-trash-fill"></em></button> @endif</td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <x-slot:additional_script>
        <script src="{{ asset('build/assets/app.b487754a.js') }}"></script>
        <script>
            let selectedModeProcurement = 0;
        
            async function openEdit(id) {
                selectedModeProcurement = id;
                await axios.get(`{{ url('/mode-of-procurement') }}/${id}`)
                    .then(res => {
                        $('#edit_name').val(res.data.name);
                        $('#editModeProcurement').modal('toggle');
                    })
                    .catch(err => alert("Could not fetch the data. Please contact website administrator."));
            }
        
            async function saveChanges(e) {
                e.preventDefault();
                const data = {
                    "name" : $('#edit_name').val(),
                };
                await axios.put(`{{ url('/mode-of-procurement') }}/${selectedModeProcurement}`, data)
                    .then(res => {
                        {{Session::forget('success')}}
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
                    let allSelectedModeProcurement = $(".modeprocurement-checkbox");
                    let toDelete = [];
                    for (let i = 0; i < allSelectedModeProcurement.length; i ++) {
                        if (allSelectedModeProcurement[i].checked) {
                            toDelete.push(allSelectedModeProcurement[i].value);
                        }
                    }
                    if (toDelete.length > 0) {
                        let confirmDeleteBatch = confirm("Are you sure to delete?");
                        if (confirmDeleteBatch) {
                            await axios.post(`{{ route('mode-procurement.delete_batch') }}`, { id : toDelete })
                                .then(res => {
                                    window.location.reload();
                                }).catch(err => {
                                    window.location.reload();
                                });
                        }
                    } else {
                        alert("Select item mode of procurement to delete first!");
                    }
                } else {
                    let confirmDeleteSingle = confirm("Are you sure to delete this mode of procurement?");
                    if (confirmDeleteSingle) {
                        await axios.delete(`{{ url('mode-of-procurement/single') }}/${id}`)
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
        @include('layout/datatable', ['tableId' => 'item-mode-procurement-table'])
    </x-slot>
</x-dashboard-layout>