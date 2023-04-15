
<x-dashboard-layout>

    <x-slot:title>
        Manage Supply Position
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Manage End User Position'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    <x-supplier-header/>
    
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-12">
                <h1 class="fw-bold h2 text-secondary">Manage End User Position</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-5">
                <div class="card">
                    <div class="card-body">
                        <h2 class="h4 text-secondary mb-4">Add End User Position</h2>
                        <form action="{{ route('supply-end-user-position.post_add') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="position_name" class="form-label">Position Name</label>
                                <input type="text" class="form-control" id="position_name" name="position_name" placeholder="Enter a position name" required>
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
                    <table id="enduser-position-table" class="table table-sm border-dark caption-top">
                        <caption class="small text-secondary">End User Position List</caption>
                        <thead>
                            <tr>
                                <th></th>
                                <th>Position Name</th>
                                <th></th>
                                <th style="disply: none;">Created at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($supply_enduser_positions as $enduser_position)
                                <tr class="small">
                                    <td class="py-3">
                                        <div class="form-check w-100 d-flex align-items-center justify-content-center">
                                            <input class="form-check-input delete-checkbox p-2 border border-dark" type="checkbox" value="{{ $enduser_position->id }}" id="enduser{{ $enduser_position->id }}" @if($enduser_position->is_delete===1) disabled @endif>
                                        </div>
                                    </td>

                                    <td class="py-3">{{ $enduser_position->position_name }} </td>
                                    <td class="text-end py-3">
                                        <div class="btn-group" role="group" aria-label="End user actions">
                                            <button onclick="openEditForm({{ $enduser_position->id }})" type="button" class="btn btn-primary btn-sm"><em class="bi bi-pencil-square"></em></button>
                                            @if($enduser_position->is_delete===1) <span class="badge bg-secondary rounded-0 rounded-end d-flex align-items-center">Deleted</span> @else <button type="button" class="btn btn-danger" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" onclick="deleteRecord({{$enduser_position->id}})"><em class="bi bi-trash-fill"></em></button> @endif
                                        </div>
                                    </td>
                                    <td style="display: none;">{{ $enduser_position->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- Update MODAL --}}
    <div class="modal fade" id="editEndUserPosition" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="openEditModalLabel" aria-hidden="true">
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
                            <label for="position_name" class="form-label">Position Name</label>
                            <input type="text" class="form-control" id="edit_position_name" name="position_name">
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
        @include('layout/datatable', ['tableId' => 'enduser-position-table' , 'columnId' => '3'])

        <script src="{{ asset('build/assets/app.b487754a.js') }}"></script>
        <script>
            let selectedEndUserPosition = 0;
        
            async function openEditForm(id) {
                selectedEndUserPosition = id;
                await axios.get(`{{ url('/manage-end-user-position') }}/${id}`)
                    .then(res => {
                        $('#edit_position_name').val(res.data.position_name);
                        $('#editEndUserPosition').modal('toggle');
                    })
                    .catch(err => alert("Could not fetch the data. Please contact website administrator."));
            }        

                    
            async function saveChanges(e) {
                e.preventDefault();
                const data = {
                    "position_name" : $('#edit_position_name').val(),
                };
                await axios.put(`{{ url('/manage-end-user-position') }}/${selectedEndUserPosition}`, data)
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
                            await axios.post(`{{ route('enduser-positions.delete_batch') }}`, { id : toDelete })
                                .then(res => {
                                    window.location.reload();
                                }).catch(err => {
                                    window.location.reload();
                                });
                        }
                    } else {
                        alert("Select Supply end user position to delete first!");
                    }
                } else {
                    let confirmDeleteSingle = confirm("Are you sure to delete this Supply End User Position from the list?");
                    if (confirmDeleteSingle) {
                        await axios.delete(`{{ url('manage-end-user-position/single') }}/${id}`)
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