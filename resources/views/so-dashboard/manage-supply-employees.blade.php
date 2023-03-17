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
                    <table id="end-users-table" class="table table-sm border-dark caption-top">
                        <caption class="small text-secondary">Supply Employees List</caption>
                        <thead>
                            <tr>
                                <th style="width: 50%;">Name</th>
                                <th style="width: 40%;">Position</th>
                                <th style="width: 10%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($supply_employees as $employee)
                                <tr class="small">
                                    <td>{{ $employee->first_name }} {{ $employee->middle_name }} {{ $employee->last_name }}</td>
                                    <td>{{ $employee->position->name }}</td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group" aria-label="End user actions">
                                            <button onclick="openEditForm()" type="button" class="btn btn-primary btn-sm"><em class="bi bi-pencil-square"></em></button>
                                            <button type="button" class="btn btn-danger btn-sm"><em class="bi bi-trash-fill"></em></button>
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
    <div class="modal fade" id="openEditModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="openEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="openEditModalLabel">Edit End User</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('supply-end-user.post_add') }}" method="POST">
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Understood</button>
                </div>
            </div>
        </div>
    </div>
    <x-slot:additional_script>
        @include('layout/datatable', ['tableId' => 'end-users-table'])
        <script src="{{ asset('build/assets/app.b487754a.js') }}"></script>
        <script>
            
            async function openEditForm() {
                $('#openEditModal').modal('show');
            }
        </script>
    </x-slot>
</x-dashboard-layout>