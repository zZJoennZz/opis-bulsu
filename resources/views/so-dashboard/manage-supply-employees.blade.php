<x-dashboard-layout>
    <x-slot:title>
        Manage Supply Employees
    </x-slot>

    @php
        $breadcrumb = [
            ['name' => '<em class="bi bi-house-fill"></em>', 'route' => 'dashboard.show'],
            ['name' => 'Manage Supply Employees'],
        ]
    @endphp

    <x-breadcrumb :breadcrumb="$breadcrumb" />
    
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-12">
                <h1 class="fw-bold h2 text-secondary">Manage Supply Employees</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-5">
                <div class="card">
                    <div class="card-body">
                        <h2 class="h4 text-secondary mb-4">Add Supply Employee</h2>
                        <form action="{{ route('supply-employee.post_add') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter end user first name here." required>
                            </div>
                            <div class="mb-3">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name" placeholder="Enter end user middle name here." required>
                            </div>
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter end user last name here." required>
                            </div>
                            <div class="mb-3">
                                <label for="position" class="form-label">Position</label>
                                <select class="form-select" id="position" name="position" aria-label="Select position" required>
                                    <option disabled selected>Select position here</option>
                                    @foreach ($positions as $position)
                                        <option value="{{ $position->id }}">{{ $position->name }}</option>
                                    @endforeach
                                </select>
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
                    <table id="end-users-table" class="table table-sm border-dark caption-top">
                        <caption class="small text-secondary">Supply Employees List</caption>
                        <thead>
                            <tr>
                                <th></th>
                                <th style="width: 50%;">Name</th>
                                <th style="width: 40%;">Position</th>
                                <th style="width: 10%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($supply_employees as $employee)
                                <tr class="small">
                                    <td class="py-3">
                                        <div class="form-check w-100 d-flex align-items-center justify-content-center">
                                            <input class="form-check-input delete-checkbox p-2 border border-dark" type="checkbox" value="{{ $employee->id }}" id="enduser{{ $employee->id }}" @if($employee->is_delete===1) disabled @endif>
                                        </div>
                                    </td>
                                    <td>{{ $employee->first_name }} {{ $employee->middle_name }} {{ $employee->last_name }}</td>
                                    <td>{{ $employee->position->name }}</td>
                                    <td class="text-end py-3">
                                        <div class="btn-group" role="group" aria-label="End user actions">
                                            <button onclick="openEditForm({{ $employee->id }})" type="button" class="btn btn-primary btn-sm"><em class="bi bi-pencil-square"></em></button>
                                            @if($employee->is_delete===1) <span class="badge bg-secondary rounded-0 rounded-end d-flex align-items-center">Deleted</span> @else <button type="button" class="btn btn-danger" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" onclick="deleteRecord({{$employee->id}})"><em class="bi bi-trash-fill"></em></button> @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="editSupplyEmployee" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="openEditModalLabel" aria-hidden="true">
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
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="edit_first_name" name="first_name" placeholder="Enter end user first name here." required>
                        </div>
                        <div class="mb-3">
                            <label for="middle_name" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" id="edit_middle_name" name="middle_name" placeholder="Enter end user middle name here." required>
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="edit_last_name" name="last_name" placeholder="Enter end user last name here." required>
                        </div>
                        <div class="mb-3">
                            <label for="position" class="form-label">Position</label>
                            <select class="form-select" id="edit_position" name="position" aria-label="Select position" required>
                                <option disabled selected>Select position here</option>
                                @foreach ($positions as $position)
                                    <option value="{{ $position->id }}">{{ $position->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <button  type="submit" class="btn btn-primary"><em class="bi bi-save2"></em> Save</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-none">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Understood</button>
                </div>
            </div>
        </div>
    </div>
    <x-supplier-header />
    <x-slot:additional_script>
        @include('layout/datatable', ['tableId' => 'end-users-table'])
        <script src="{{ asset('build/assets/app.b487754a.js') }}"></script>
        <script>
            let selectedSupplyEmployee = 0;
        
            async function openEditForm(id) {
                selectedSupplyEmployee = id;
                await axios.get(`{{ url('/manage-supply-employee') }}/${id}`)
                    .then(res => {
                        $('#edit_first_name').val(res.data.first_name);
                        $('#edit_middle_name').val(res.data.middle_name);
                        $('#edit_last_name').val(res.data.last_name);
                        $('#edit_position').val(res.data.supply_positions_id);
                        $('#editSupplyEmployee').modal('toggle');
                    })
                    .catch(err => alert("Could not fetch the data. Please contact website administrator."));
            }        

                    
            async function saveChanges(e) {
                e.preventDefault();
                const data = {
                    "first_name" : $('#edit_first_name').val(),
                    "middle_name" : $('#edit_middle_name').val(),
                    "last_name" : $('#edit_last_name').val(),
                    "position" : $('#edit_position').val()
                };
                await axios.put(`{{ url('/manage-supply-employee') }}/${selectedSupplyEmployee}`, data)
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
                            await axios.post(`{{ route('supplyemployee.delete_batch') }}`, { id : toDelete })
                                .then(res => {
                                    window.location.reload();
                                }).catch(err => {
                                    window.location.reload();
                                });
                        }
                    } else {
                        alert("Select Enduser to delete first!");
                    }
                } else {
                    let confirmDeleteSingle = confirm("Are you sure to delete this supply employee?");
                    if (confirmDeleteSingle) {
                        await axios.delete(`{{ url('manage-supply-employee/single') }}/${id}`)
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